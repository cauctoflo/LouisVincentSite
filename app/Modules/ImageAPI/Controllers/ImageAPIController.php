<?php

namespace App\Modules\ImageAPI\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageApi;


class ImageAPIController extends Controller
{
    public $imageapi = "ImageAPI";
    
    /**
     * Display the index page for the ImageAPI module.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('ImageAPI::index');
    }


    private function LogIfDebug($message) {
        if (config('app.debug')) {
            \Log::debug($message);
        }
    }

    /**
     * Store a new image in the ImageAPI module.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        
        $this->LogIfDebug('Request data: ' . json_encode($request->all()));

        $request->validate([
            'images' => 'required|array',
            'images.0' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:100',
            'tag' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'group_id' => 'nullable|integer|exists:module_imageapi_groups,id',
        ]);

        $token = $this->generateToken();


        $this->LogIfDebug('Validation passed, proceeding with image upload.');
        
        $imageFile = $request->file('images')[0];
        
        $imagePath = $imageFile->storeAs(
            'images', $token . '.' . $imageFile->getClientOriginalExtension(), 'public'
        );

        $this->LogIfDebug('Image stored at: ' . $imagePath);

        $imageApi = new ImageApi();
        $imageApi->token = $token;
        $imageApi->path = $imagePath;
        $imageApi->description = $request->input('description', '');
        $imageApi->name = $request->input('title', '');
        $imageApi->tags = json_encode([$request->input('tag', '')]);
        $imageApi->alt_text = $request->input('alt_text', '');
        $imageApi->status = $request->input('status', 'active');
        $imageApi->group_id = $request->input('group_id') ?: null;
        $imageApi->save();

        $this->LogIfDebug('Image metadata saved to database with token: ' . $token);
        
        // Prepare success message
        $message = 'Image ajoutée avec succès!';
        if ($request->input('group_id')) {
            $folder = \App\Models\ImageAPIGroups::find($request->input('group_id'));
            if ($folder) {
                $message .= ' Elle a été placée dans le dossier "' . $folder->name . '".';
            }
        } else {
            $message .= ' Elle a été placée à la racine.';
        }
        
        // Return a success response with redirect
        return redirect()->route('personnels.ImageAPI.index')->with('message', $message);
    }


    private function generateToken() {
        // Generate a unique token for the image
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($characters, ceil(32/strlen($characters)))), 1, 32);
    }



    /**
     * Retrieve an image by its token.
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Request $request, $token) {
        // $this->LogIfDebug('Attempting to retrieve image with token: ' . $token);

        $imageApi = ImageApi::where('id', $request->image_id)->first();


        if (!$imageApi) {
            $this->LogIfDebug('Request data: ' . json_encode($request->all()));

            // $this->LogIfDebug('Image with token ' . $token . ' not found.');
            return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image introuvable');
        }

        // Dans un cas réel, vous pourriez vouloir rediriger vers une vue détaillée de l'image
        return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image trouvée');
    }

    /**
     * Update an existing image in the ImageAPI module.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->LogIfDebug('Update request data: ' . json_encode($request->all()));

        $request->validate([
            'image_id' => 'required|integer',
            'title' => 'nullable|string|max:100',
            'tag' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageApi = ImageApi::find($request->image_id);

        if (!$imageApi) {
            return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image introuvable');
        }

        // Update text metadata
        $imageApi->name = $request->input('title', $imageApi->name);
        $imageApi->description = $request->input('description', $imageApi->description);
        $imageApi->tags = json_encode([$request->input('tag', '')]);
        $imageApi->alt_text = $request->input('alt_text', $imageApi->alt_text);
        $imageApi->status = $request->input('status', $imageApi->status);
        
        // If a new image file is provided, update the image file
        if ($request->hasFile('image')) {
            // Delete the old image file
            if (\Storage::disk('public')->exists($imageApi->path)) {
                \Storage::disk('public')->delete($imageApi->path);
            }
            
            // Store the new image file
            $imageFile = $request->file('image');
            $token = $imageApi->token ?? $this->generateToken();
            
            $imagePath = $imageFile->storeAs(
                'images', $token . '.' . $imageFile->getClientOriginalExtension(), 'public'
            );
            
            $imageApi->path = $imagePath;
            $imageApi->token = $token;
        }
        
        $imageApi->save();
        
        $this->LogIfDebug('Image updated successfully with ID: ' . $request->image_id);
        
        return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image mise à jour avec succès!');
    }

    /**
     * Delete an image.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request) {
        $this->LogIfDebug('Request data for deletion: ' . json_encode($request->all()));

        $request->validate([
            'image_id' => 'required|integer',
        ]);

        $imageId = $request->input('image_id');

        $this->LogIfDebug('Attempting to delete image with image_id: ' . $imageId);
        $imageApi = ImageApi::where('id', $imageId)->first();

        if (!$imageApi) {
            $this->LogIfDebug('Image with image_id ' . $imageId . ' not found.');
            return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image introuvable');
        }

        $this->LogIfDebug('Image with image_id ' . $imageId . ' found, proceeding with deletion.');
        \Storage::disk('public')->delete($imageApi->path);
        $imageApi->delete();
        $this->LogIfDebug('Image metadata deleted from database for image_id: ' . $imageId);
        $this->LogIfDebug('Image with image_id ' . $imageId . ' deleted successfully.');

        return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image supprimée avec succès');
    }

    public function getImagebyToken($token) {
        $this->LogIfDebug('Attempting to retrieve image with token: ' . $token);

        $imageApi = ImageApi::where('token', $token)->first();
        if (!$imageApi) {
            $this->LogIfDebug('Image with token ' . $token . ' not found.');
            return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image introuvable');
        }
        if ($imageApi->status !== "public") {
            $request = request();
            $referer = $request->headers->get('referer');
            $isLocalRequest = $referer && (
                str_contains($referer, config('app.url')) || 
                str_contains($referer, 'localhost') || 
                str_contains($referer, '127.0.0.1')
            );
            
            // If the image is non-public and the request isn't from our own server/site
            if (!$isLocalRequest) {
                $this->LogIfDebug('Image with token ' . $token . ' is not public and request is not from our site.');
                return response()->json([
                    'error' => 'Unauthorized access to this image.'
                ], 403);
            }
        }
        $this->LogIfDebug('Image with token ' . $token . ' found, returning image path.');
        return response()->file(storage_path('app/public/' . $imageApi->path));
    }
    public function createFolder() {
        $this->LogIfDebug('Creating folder');
        // Check if the folder name is valid
        $request = request();
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Create the new folder/group
        $folder = new \App\Models\ImageAPIGroups();
        $folder->name = $request->input('name');
        $folder->description = $request->input('description');
        $folder->group_id = $request->input('parent_id'); 
        $folder->sort = $request->input('sort', 0);
        $folder->icon = $request->input('icon');
        $folder->color = $request->input('color');
        $folder->is_active = $request->input('is_active', 1);
        $folder->max_size = $request->input('max_size', 100);
        $folder->allowed_types = $request->input('allowed_types', 'jpg,jpeg,png,gif,webp');
        $folder->save();

        $this->LogIfDebug('Folder created with ID: ' . $folder->id);
        return redirect()->route('personnels.ImageAPI.index')->with('message', 'Dossier créé avec succès!');
    }

    /**
     * Update an existing folder/group.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFolder(Request $request)
    {
        $this->LogIfDebug('Update folder request data: ' . json_encode($request->all()));

        $request->validate([
            'folder_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'group_id' => 'nullable|integer',
            'sort' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'max_size' => 'nullable|numeric|min:0.1|max:1000',
            'allowed_types' => 'nullable|string|max:255',
        ]);

        $folder = \App\Models\ImageAPIGroups::find($request->folder_id);

        if (!$folder) {
            $this->LogIfDebug('Folder with ID ' . $request->folder_id . ' not found.');
            return redirect()->route('personnels.ImageAPI.index')->with('error', 'Dossier introuvable');
        }

        // Update folder data
        $folder->name = $request->input('name');
        $folder->description = $request->input('description');
        $folder->group_id = $request->input('group_id'); // Parent folder
        $folder->sort = $request->input('sort', 0);
        $folder->icon = $request->input('icon');
        $folder->color = $request->input('color');
        $folder->is_active = $request->has('is_active') ? 1 : 0;
        $folder->max_size = $request->input('max_size', 100);
        $folder->allowed_types = $request->input('allowed_types', 'jpg,jpeg,png,gif,webp');
        
        $folder->save();

        $this->LogIfDebug('Folder updated successfully with ID: ' . $folder->id);
        
        return redirect()->route('personnels.ImageAPI.index')->with('success', 'Dossier modifié avec succès!');
    }

    /**
     * Delete a folder/group.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFolder(Request $request)
    {
        $this->LogIfDebug('Delete folder request data: ' . json_encode($request->all()));

        $request->validate([
            'folder_id' => 'required|integer',
        ]);

        $folderId = $request->input('folder_id');
        $folder = \App\Models\ImageAPIGroups::find($folderId);

        if (!$folder) {
            $this->LogIfDebug('Folder with ID ' . $folderId . ' not found.');
            return redirect()->route('personnels.ImageAPI.index')->with('error', 'Dossier introuvable');
        }

        // Check if folder has child folders
        $childFolders = \App\Models\ImageAPIGroups::where('group_id', $folderId)->count();
        if ($childFolders > 0) {
            $this->LogIfDebug('Cannot delete folder with ID ' . $folderId . ' because it has child folders.');
            return redirect()->route('personnels.ImageAPI.index')->with('error', 'Impossible de supprimer ce dossier car il contient des sous-dossiers. Veuillez d\'abord supprimer ou déplacer les sous-dossiers.');
        }

        // Move all images in this folder to root (group_id = null)
        $imagesCount = ImageApi::where('group_id', $folderId)->count();
        if ($imagesCount > 0) {
            ImageApi::where('group_id', $folderId)->update(['group_id' => null]);
            $this->LogIfDebug('Moved ' . $imagesCount . ' images from folder ID ' . $folderId . ' to root.');
        }

        $folderName = $folder->name;
        $folder->delete();

        $this->LogIfDebug('Folder "' . $folderName . '" deleted successfully with ID: ' . $folderId);
        
        $message = 'Dossier "' . $folderName . '" supprimé avec succès!';
        if ($imagesCount > 0) {
            $message .= ' ' . $imagesCount . ' image(s) ont été déplacée(s) vers la racine du drive.';
        }
        
        return redirect()->route('personnels.ImageAPI.index')->with('success', $message);
    }

    /**
     * Get the count of images in a specific folder.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFolderImagesCount($id) {
        try {
            $count = ImageApi::where('group_id', $id)->count();
            
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            $this->LogIfDebug('Error getting folder images count: ' . $e->getMessage());
            return response()->json(['count' => 0, 'error' => 'Erreur lors du comptage des images'], 500);
        }
    }

}
