<?php

namespace Tec\Base\Repositories\Eloquent;

use Tec\Base\Models\BaseModel;
use Tec\Base\Models\BaseQueryBuilder;
use Tec\Base\Models\MetaBox;
use Tec\Base\Repositories\Interfaces\MetaBoxInterface;
use Tec\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MetaBoxRepository extends RepositoriesAbstract implements MetaBoxInterface
{
    public function __construct(protected BaseModel|BaseQueryBuilder|Builder|Model $model)
    {
        parent::__construct(new MetaBox());
    }
}
