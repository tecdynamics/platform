<?php

namespace Tec\Media\Http\Controllers;

use Tec\Base\Http\Controllers\BaseController;
use Tec\Media\Facades\RvMedia;
use Tec\Media\Models\MediaFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PublicMediaController extends BaseController
{
    public function show(string $hash, string $id)
    {
        $originId = MediaFile::isUsingStringId() ? $id : hexdec($id);

        if (sha1($id) !== $hash) {
            abort(404);
        }

        $mediaFile = MediaFile::query()
            ->whereKey($originId)
            ->firstOrFail();

        if ($mediaFile->visibility === 'private') {
            return response()->download(Storage::disk('local')->path($mediaFile->url));
        }

        $response = Http::get(RvMedia::url($mediaFile->url));

        if ($response->ok()) {
            $body = $response->toPsrResponse()->getBody();

            return Response::streamDownload(function () use ($body) {
                while (! $body->eof()) {
                    echo $body->read(1024);
                }
            }, headers: $response->headers());
        }

        abort(403);
    }
}
