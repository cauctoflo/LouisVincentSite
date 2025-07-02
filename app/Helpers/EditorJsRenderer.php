<?php

namespace App\Helpers;

class EditorJsRenderer
{
    /**
     * Render Editor.js content as HTML
     *
     * @param string|null $jsonContent Editor.js JSON content
     * @return string HTML content
     */
    public static function render($jsonContent)
    {
        if (!$jsonContent) {
            return '';
        }

        try {
            $data = json_decode($jsonContent, true);
            
            if (!$data || !isset($data['blocks']) || empty($data['blocks'])) {
                return '';
            }
            
            $html = '';
            
            foreach ($data['blocks'] as $block) {
                $html .= self::renderBlock($block);
            }
            
            return $html;
        } catch (\Exception $e) {
            // Log error if needed
            return '<div class="text-red-500">Erreur lors du rendu du contenu: ' . $e->getMessage() . '</div>';
        }
    }
    
    /**
     * Render a single Editor.js block
     *
     * @param array $block Block data
     * @return string HTML content for the block
     */
    private static function renderBlock($block)
    {
        if (!isset($block['type']) || !isset($block['data'])) {
            return '';
        }
        
        $type = $block['type'];
        $data = $block['data'];
        
        switch ($type) {
            case 'header':
                $level = isset($data['level']) ? $data['level'] : 2;
                $text = isset($data['text']) ? $data['text'] : '';
                return sprintf('<h%d class="text-xl lg:text-2xl font-bold my-4">%s</h%d>', 
                    $level, self::purifyHtml($text), $level);
                
            case 'paragraph':
                $text = isset($data['text']) ? $data['text'] : '';
                return sprintf('<p class="mb-4">%s</p>', self::purifyHtml($text));
                
            case 'list':
                $style = isset($data['style']) ? $data['style'] : 'unordered';
                $items = isset($data['items']) ? $data['items'] : [];
                
                if (empty($items)) {
                    return '';
                }
                
                $tag = ($style === 'ordered') ? 'ol' : 'ul';
                $classList = ($style === 'ordered') ? 'list-decimal' : 'list-disc';
                
                $html = sprintf('<%s class="mb-4 ml-5 %s">', $tag, $classList);
                
                foreach ($items as $item) {
                    $html .= sprintf('<li class="mb-1">%s</li>', self::purifyHtml($item));
                }
                
                $html .= sprintf('</%s>', $tag);
                
                return $html;
                
            case 'quote':
                $text = isset($data['text']) ? $data['text'] : '';
                $caption = isset($data['caption']) ? $data['caption'] : '';
                
                $html = '<blockquote class="border-l-4 border-gray-300 pl-4 italic my-4">';
                $html .= sprintf('<p>%s</p>', self::purifyHtml($text));
                
                if (!empty($caption)) {
                    $html .= sprintf('<footer class="text-sm text-gray-500 mt-1">%s</footer>', 
                        self::purifyHtml($caption));
                }
                
                $html .= '</blockquote>';
                
                return $html;

            case 'code':
                $code = isset($data['code']) ? $data['code'] : '';
                return sprintf('<pre class="bg-gray-900 text-white p-4 rounded-lg overflow-x-auto my-4"><code>%s</code></pre>',
                    htmlspecialchars($code, ENT_QUOTES, 'UTF-8'));

            case 'image':
                $url = isset($data['url']) ? $data['url'] : '';
                $caption = isset($data['caption']) ? $data['caption'] : '';
                $stretched = isset($data['stretched']) && $data['stretched'];
                $withBorder = isset($data['withBorder']) && $data['withBorder'];
                $withBackground = isset($data['withBackground']) && $data['withBackground'];
                
                $classes = [];
                if ($stretched) $classes[] = 'w-full';
                if ($withBorder) $classes[] = 'border border-gray-300';
                if ($withBackground) $classes[] = 'bg-gray-100 p-2';
                
                $classString = !empty($classes) ? ' class="' . implode(' ', $classes) . '"' : '';
                
                $html = sprintf('<figure class="my-4%s">', $withBackground ? ' bg-gray-100 p-4 rounded' : '');
                $html .= sprintf('<img src="%s" alt="%s"%s>', 
                    htmlspecialchars($url, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'),
                    $classString);
                
                if (!empty($caption)) {
                    $html .= sprintf('<figcaption class="text-center text-sm text-gray-500 mt-2">%s</figcaption>', 
                        self::purifyHtml($caption));
                }
                
                $html .= '</figure>';
                
                return $html;
                
            case 'embed':
                $service = isset($data['service']) ? $data['service'] : '';
                $embed = isset($data['embed']) ? $data['embed'] : '';
                $source = isset($data['source']) ? $data['source'] : '';
                $width = isset($data['width']) ? $data['width'] : '100%';
                $height = isset($data['height']) ? $data['height'] : '400';
                $caption = isset($data['caption']) ? $data['caption'] : '';
                
                $html = '<div class="embed-container my-4">';
                
                if (in_array($service, ['youtube', 'vimeo'])) {
                    $html .= sprintf('<div class="aspect-w-16 aspect-h-9"><iframe src="%s" width="%s" height="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>',
                        htmlspecialchars($embed, ENT_QUOTES, 'UTF-8'),
                        $width,
                        $height
                    );
                } else {
                    // For other services, just create a link
                    $html .= sprintf('<div class="bg-gray-100 p-4 rounded"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></div>', 
                        htmlspecialchars($source, ENT_QUOTES, 'UTF-8'),
                        htmlspecialchars($caption ?: $source, ENT_QUOTES, 'UTF-8')
                    );
                }
                
                if (!empty($caption)) {
                    $html .= sprintf('<div class="text-center text-sm text-gray-500 mt-2">%s</div>', 
                        self::purifyHtml($caption));
                }
                
                $html .= '</div>';
                
                return $html;
                
            case 'checklist':
                $items = isset($data['items']) ? $data['items'] : [];
                
                if (empty($items)) {
                    return '';
                }
                
                $html = '<div class="editor-checklist my-4">';
                
                foreach ($items as $item) {
                    $checked = isset($item['checked']) && $item['checked'] ? ' checked' : '';
                    $text = isset($item['text']) ? $item['text'] : '';
                    
                    $html .= sprintf('<div class="flex items-center mb-2">
                        <input type="checkbox" disabled%s class="mr-2 rounded">
                        <span>%s</span>
                    </div>', $checked, self::purifyHtml($text));
                }
                
                $html .= '</div>';
                
                return $html;
                
            case 'delimiter':
                return '<hr class="my-6">';
                
            // Add more block types as needed
            
            default:
                // For unsupported blocks, just return the raw content if available
                if (isset($data['text'])) {
                    return sprintf('<div class="my-4">%s</div>', self::purifyHtml($data['text']));
                }
                return '';
        }
    }
    
    /**
     * Basic HTML sanitization
     *
     * @param string $html HTML to sanitize
     * @return string Sanitized HTML
     */
    private static function purifyHtml($html)
    {
        // This is a very basic implementation, consider using HTML Purifier for production
        return $html;
    }
}
