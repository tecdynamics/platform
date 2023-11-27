<?php

namespace Tec\Base\Supports;

use App;
use Assets;
use BaseHelper;
use Illuminate\Support\Arr;
use Throwable;

class Editor
{
    public function __construct()
    {
        add_action(BASE_ACTION_ENQUEUE_SCRIPTS, [$this, 'registerAssets'], 12);
    }

    public function registerAssets()
    {
        Assets::addScriptsDirectly(
            config('core.base.general.editor.' . BaseHelper::getRichEditor() . '.js')
        )
            ->addScriptsDirectly('vendor/core/core/base/js/editor.js');
        if (BaseHelper::getRichEditor() == 'ckeditor' && App::getLocale() != 'en') {
            Assets::addScriptsDirectly('https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/translations/' . App::getLocale() . '.js');
        }

        return $this;
    }

    /**
     * @param string $name
     * @param null $value
     * @param bool $withShortcode
     * @param array $attributes
     * @return string
     * @throws Throwable
     */
    public function render($name, $value = null, $withShortcode = false, array $attributes = [])
    {
        $attributes['class'] = Arr::get($attributes, 'class', '') . ' editor-' . BaseHelper::getRichEditor();

        $attributes['id'] = Arr::has($attributes, 'id') ? $attributes['id'] : $name;
        $attributes['with-short-code'] = $withShortcode;
        $attributes['rows'] = Arr::get($attributes, 'rows', 4);

        return view('core/base::forms.partials.editor', compact('name', 'value', 'attributes'))
            ->render();
    }
}
