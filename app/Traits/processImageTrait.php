<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait processImageTrait{

    public function photo(Request $request, $directory)
    {
        $request->validate([
            'photo' => 'array',
            'photo.*' => 'image
            |dimensions:width=3840,height=2160
            |mimes:gif,png,jpg,PNG,JPG,GIF
            |max:2700',
        ]);

        $imagePaths = [];

        foreach ($request->file('photo') as $photo) {
            $imageName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path($directory), $imageName);
            $imagePaths[] = $imageName;
        }

        return $imagePaths;
    }

}
