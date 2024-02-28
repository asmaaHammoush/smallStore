<?php
namespace App\Traits;

use App\Models\Image;
use Illuminate\Http\Request;

trait processImageTrait{

    public function uploadPhoto(Request $request, $directory)
    {
        $imagePaths = [];

        foreach ($request->file('photo') as $photo) {
            $imageName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path($directory), $imageName);
            $imagePaths[] = public_path($directory) . '/' . $imageName;
        }

        return $imagePaths;
    }

    public function deletePhoto($photoPath)
    {
        if (file_exists($photoPath)) {
            unlink($photoPath);
            return true;
        }

        return false;
    }

    public function updatePhoto($request, $oldPhoto,$directory)
    {
        if (file_exists($oldPhoto)) {
            unlink($oldPhoto);
        }

        $imagePaths = [];
        foreach ($request->file('photo') as $photo) {
            $imageName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path($directory), $imageName);
            $imagePaths[] = public_path($directory) . '/' . $imageName;
        }

        return $imagePaths;
    }

    public function updatePhotoProduct($photo,$oldPhoto, $directory)
    {
        foreach ($oldPhoto as $imageId) {
            $image = Image::find($imageId);
            if ($image && file_exists($image->photo)) {
                unlink($image->photo);
            }
        }

        $imageName = time() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path($directory), $imageName);
        $imagePath = public_path($directory) . '/' . $imageName;

        return $imagePath;
    }

}
