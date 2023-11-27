<?php

namespace Tec\Table\Actions;

use Tec\Table\Abstracts\TableActionAbstract;
use Tec\Table\Actions\Concerns\HasAction;
use Tec\Table\Actions\Concerns\HasAttributes;
use Tec\Table\Actions\Concerns\HasColor;
use Tec\Table\Actions\Concerns\HasIcon;
use Tec\Table\Actions\Concerns\HasUrl;

class Action extends TableActionAbstract
{
    use HasAction;
    use HasAttributes;
    use HasColor;
    use HasIcon;
    use HasUrl;
}
