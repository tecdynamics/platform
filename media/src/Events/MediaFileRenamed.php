<?php

namespace Tec\Media\Events;

use Tec\Media\Models\MediaFile;
use Illuminate\Foundation\Events\Dispatchable;

class MediaFileRenamed
{
    use Dispatchable;

    public function __construct(public MediaFile $file)
    {
    }
}
