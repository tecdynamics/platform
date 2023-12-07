<?php

namespace Tec\Media\Http\Resources;

use Tec\Base\Facades\BaseHelper;
use Tec\Media\Facades\RvMedia;
use Tec\Media\Models\MediaFile;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

/**
 * @mixin MediaFile
 */
class FileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'basename' => File::basename($this->url),
            'url' => $this->url,
            'full_url' => RvMedia::url($this->url),
            'type' => $this->type,
            'icon' => $this->icon,
            'thumb' => $this->canGenerateThumbnails() ? RvMedia::getImageUrl($this->url, 'thumb') : null,
            'size' => $this->human_size,
            'mime_type' => $this->mime_type,
            'created_at' => BaseHelper::formatDate($this->created_at, 'Y-m-d H:i:s'),
            'updated_at' => BaseHelper::formatDate($this->updated_at, 'Y-m-d H:i:s'),
            'options' => $this->options,
            'folder_id' => $this->folder_id,
            'preview_url' => $this->preview_url,
            'preview_type' => $this->preview_type,
            'alt' => $this->alt,
        ];
    }
}
