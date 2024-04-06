<?php

namespace Tec\Base\Traits\Forms;

use Tec\Base\Forms\FormCollapse;

trait HasCollapsible
{
    public function addCollapsible(FormCollapse $form): static
    {
        $form->build($this);

        return $this;
    }
}
