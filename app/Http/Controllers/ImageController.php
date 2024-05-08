<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:png|max:2048', // Adjust max file size as needed
        ]);

        // Get the uploaded file
        $imageData = file_get_contents($request->file('image'));

        // Store the image data in the database
        $image = new Image();
        $image->image_data = $imageData;
        $image->save();

        return response()->json(['message' => 'Image uploaded successfully']);
    }
}