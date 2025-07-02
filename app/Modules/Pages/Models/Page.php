<?php

namespace App\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Log\Traits\Loggable;
use Carbon\Carbon;

class Page extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'folder_id',
        'section_id',
        'created_by',
        'updated_by',
        'is_published',
        'published_at',
        'order_index',
        'meta_title',
        'meta_description',
        'tags'
    ];

    protected $casts = [
        'content' => 'json',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
        'order_index' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Log attributes to be recorded
     */
    protected $logAttributes = [
        'title',
        'slug',
        'folder_id',
        'section_id',
        'is_published',
        'published_at',
        'order_index'
    ];

    /**
     * Custom descriptions for log events
     */
    protected $logDescriptions = [
        'created' => 'Page créée',
        'updated' => 'Page mise à jour',
        'deleted' => 'Page supprimée'
    ];

    /**
     * Relation avec la section
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Relation avec le dossier
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a fait la dernière modification
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relation avec l'historique des modifications
     */
    public function revisions()
    {
        return $this->hasMany(PageRevision::class)->orderBy('created_at', 'desc');
    }

    /**
     * Scope pour les pages publiées
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope pour les brouillons
     */
    public function scopeDrafts($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope pour recherche plein-texte
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('excerpt', 'LIKE', "%{$term}%")
              ->orWhere('content->blocks', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Génère automatiquement le slug à partir du titre
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (!$this->slug) {
            $this->attributes['slug'] = \Str::slug($value);
        }
    }

    /**
     * Récupère l'URL publique de la page
     */
    public function getPublicUrlAttribute()
    {
        if ($this->folder) {
            return route('pages.show', [
                'section' => $this->section->slug,
                'folder' => $this->folder->slug,
                'page' => $this->slug
            ]);
        }
        
        return route('pages.show', [
            'section' => $this->section->slug,
            'page' => $this->slug
        ]);
    }

    /**
     * Récupère le contenu en HTML
     */
    public function getContentHtmlAttribute()
    {
        if (is_array($this->content) && isset($this->content['blocks'])) {
            // Pour Editor.js - conversion des blocks en HTML
            return $this->convertEditorJsToHtml($this->content);
        }
        
        // Si c'est déjà du HTML
        return $this->content;
    }

    /**
     * Récupère l'extrait de la page
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Génère un extrait depuis le contenu
        $text = '';
        if (is_array($this->content) && isset($this->content['blocks'])) {
            foreach ($this->content['blocks'] as $block) {
                if ($block['type'] === 'paragraph' && isset($block['data']['text'])) {
                    $text .= strip_tags($block['data']['text']) . ' ';
                }
            }
        } else {
            $text = strip_tags($this->content);
        }

        return \Str::limit($text, 200);
    }

    /**
     * Publie la page
     */
    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now()
        ]);

        return $this;
    }

    /**
     * Dépublie la page
     */
    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'published_at' => null
        ]);

        return $this;
    }

    /**
     * Crée une révision de la page
     */
    public function createRevision()
    {
        return $this->revisions()->create([
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'created_by' => auth()->id(),
            'revision_type' => 'manual'
        ]);
    }

    /**
     * Convertit le contenu Editor.js en HTML
     */
    private function convertEditorJsToHtml($content)
    {
        if (!isset($content['blocks'])) {
            return '';
        }

        $html = '';
        foreach ($content['blocks'] as $block) {
            switch ($block['type']) {
                case 'paragraph':
                    $html .= '<p>' . ($block['data']['text'] ?? '') . '</p>';
                    break;
                case 'header':
                    $level = $block['data']['level'] ?? 2;
                    $html .= '<h' . $level . '>' . ($block['data']['text'] ?? '') . '</h' . $level . '>';
                    break;
                case 'list':
                    $tag = ($block['data']['style'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul';
                    $html .= '<' . $tag . '>';
                    foreach ($block['data']['items'] ?? [] as $item) {
                        $html .= '<li>' . $item . '</li>';
                    }
                    $html .= '</' . $tag . '>';
                    break;
                case 'image':
                    $url = $block['data']['file']['url'] ?? '';
                    $caption = $block['data']['caption'] ?? '';
                    $html .= '<figure>';
                    $html .= '<img src="' . $url . '" alt="' . $caption . '">';
                    if ($caption) {
                        $html .= '<figcaption>' . $caption . '</figcaption>';
                    }
                    $html .= '</figure>';
                    break;
                case 'quote':
                    $html .= '<blockquote>';
                    $html .= '<p>' . ($block['data']['text'] ?? '') . '</p>';
                    if (!empty($block['data']['caption'])) {
                        $html .= '<cite>' . $block['data']['caption'] . '</cite>';
                    }
                    $html .= '</blockquote>';
                    break;
                default:
                    // Pour les blocs non reconnus, affiche le contenu brut
                    if (isset($block['data']['text'])) {
                        $html .= '<div>' . $block['data']['text'] . '</div>';
                    }
            }
        }

        return $html;
    }
}
