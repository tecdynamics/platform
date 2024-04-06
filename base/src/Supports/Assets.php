<?php

namespace Tec\Base\Supports;

use Tec\Assets\Assets as BaseAssets;
use Tec\Assets\HtmlBuilder;
use Tec\Base\Facades\AdminHelper;
use Tec\Base\Facades\BaseHelper;
use Illuminate\Config\Repository;

class Assets extends BaseAssets
{
    protected bool $hasVueJs = false;

    public function __construct(Repository $config, HtmlBuilder $htmlBuilder)
    {
        parent::__construct($config, $htmlBuilder);

        $this->config = $config->get('core.base.assets');

        $this->scripts = $this->config['scripts'];

        $this->styles = $this->config['styles'];
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getThemes(): array
    {
        return [];
    }

    public function renderHeader($lastStyles = []): string
    {
        do_action(BASE_ACTION_ENQUEUE_SCRIPTS);

        if (AdminHelper::isInAdmin(true) && BaseHelper::adminLanguageDirection() === 'rtl') {
            $this->config['resources']['styles']['core']['src']['local'] = '/vendor/core/core/base/css/core.rtl.css';
            $this->config['resources']['styles']['select2']['src']['local'][1] = '/vendor/core/core/base/css/libraries/select2.rtl.css';
        }

        return parent::renderHeader($lastStyles);
    }

    public function renderFooter(): string
    {
        $bodyScripts = $this->getScripts(self::ASSETS_SCRIPT_POSITION_FOOTER);

        return view('assets::footer', compact('bodyScripts'))->render();
    }

    public function usingVueJS(): self
    {
        $this->addScripts(['vue', 'vue-app']);

        $this->hasVueJs = true;

        return $this;
    }

    public function disableVueJS(): self
    {
        $this->removeScripts(['vue', 'vue-app']);

        $this->hasVueJs = false;

        return $this;
    }

    public function hasVueJs(): bool
    {
        return $this->hasVueJs;
    }

    public function getAdminLocales(): array
    {
        return Language::getAvailableLocales();
    }
}
