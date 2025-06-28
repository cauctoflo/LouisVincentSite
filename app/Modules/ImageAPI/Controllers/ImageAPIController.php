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
        $imageApi->save();

        $this->LogIfDebug('Image metadata saved to database with token: ' . $token);
        
        // Return a success response with redirect
        return redirect()->route('personnels.ImageAPI.index')->with('message', 'Image ajoutée avec succès!');
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
        $this->LogIfDebug('Image with token ' . $token . ' found, returning image path.');
        return response()->file(storage_path('app/public/' . $imageApi->path));
    }

}
