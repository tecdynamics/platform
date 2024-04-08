<?php

namespace Tec\Table\Actions;

use Tec\Base\Supports\Builders\HasAttributes;
use Tec\Base\Supports\Builders\HasColor;
use Tec\Base\Supports\Builders\HasIcon;
use Tec\Base\Supports\Builders\HasUrl;
use Tec\Table\Abstracts\TableActionAbstract;
use Tec\Table\Actions\Concerns\HasAction;

class Action extends TableActionAbstract
{
    use HasAction;
    use HasAttributes;
    use HasColor;
    use HasIcon;
    use HasUrl;

    protected string $type = 'a';

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCssClass(): string
    {
        if ($this->getAttribute('class')) {
            return '';
        }

        $classes = [
            'btn',
            'btn-sm',
        ];

        if ($this->hasIcon()) {
            $classes[] = 'btn-icon';
        }

        $classes[] = $this->getColor();

        return implode(' ', $classes);
    }
}
