<?php

namespace App\Traits;

trait ImageTrait
{

  public function singleUpload(\Illuminate\Http\Request $request, $field, $path)
  {
    $file = $request->file($field);
    $path = $path . '/' . date('FY') . '/';
    $fullPath = image_name($path, $file->getClientOriginalExtension());

    $image = \Image::make($file)->encode($file->getClientOriginalExtension(), 75);
    \Storage::disk('customer')->put($fullPath, $image, 'public');

    return $fullPath;
  }

  public function removeFile($file)
  {
    \Storage::disk('customer')->delete($file);
  }
}
