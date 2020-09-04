<?php
if (!function_exists('image_name')) {
  function image_name($path, $extension)
  {
    $fileName = \Str::random(20);
    while (Storage::disk('customer')->exists($path . $fileName . $extension)) {
      $fileName = \Str::random(20);
    }

    return $path . $fileName . '.' . $extension;
  }
}

if (!function_exists('format_price_id')) {
  function format_price_id($value, $currency = 'Rp')
  {
    return $currency . ' ' . number_format($value, 0, ',', '.');
  }
}

if (!function_exists('image_url')) {
  function image_url($file, $default = '')
  {
    if (!empty($file)) {
      return Storage::disk('customer')->url($file);
    }

    return $default;
  }
}

if (!function_exists('num_uf')) {
  function num_uf($number)
  {

    return str_replace('.', '', $number);
  }
}
