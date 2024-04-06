<?php

use Tec\Media\Facades\RvMedia;
use Illuminate\Http\UploadedFile;

if (! function_exists('is_image')) {
    function is_image(string $mimeType): bool
    {
        return RvMedia::isImage($mimeType);
    }
}

if (! function_exists('get_image_url')) {

    function get_image_url(
        string $url,
        string|null $size = null,
        bool $relativePath = false,
        $default = null
    ): string|null {
        return RvMedia::getImageUrl($url, $size, $relativePath, $default);
    }
}

if (! function_exists('get_object_image')) {

    function get_object_image(string $image, string|null $size = null, bool $relativePath = false): string|null
    {
        return RvMedia::getImageUrl($image, $size, $relativePath, RvMedia::getDefaultImage());
    }
}

if (! function_exists('rv_media_handle_upload')) {

    function rv_media_handle_upload(?UploadedFile $fileUpload, int|string $folderId = 0, string $path = ''): array
    {
        return RvMedia::handleUpload($fileUpload, $folderId, $path);
    }
}
