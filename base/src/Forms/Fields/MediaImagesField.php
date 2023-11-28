<?php

namespace Tec\Base\Forms\Fields;

use Tec\Base\Facades\Assets;
use Tec\Base\Forms\FormField;

class MediaImagesField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScripts(['jquery-ui']);

        return 'core/base::forms.fields.media-images';
    }
}
