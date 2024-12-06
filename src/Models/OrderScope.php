<?php
/**
 *  ****************************************************************
 *  *** DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER. ***
 *  ****************************************************************
 *  Copyright © 2023 TEC-Dynamics LTD <support@tecdynamics.org>.
 *  All rights reserved.
 *  This software contains confidential proprietary information belonging
 *  to Tec-Dynamics Software Limited. No part of this information may be used, reproduced,
 *  or stored without prior written consent of Tec-Dynamics Software Limited.
 * @Author    : Michail Fragkiskos
 * @Created at: 15/09/2023 at 19:50
 * @Interface     : OrderScope
 * @Package   : tec_new
 * @package App\Models
 */

namespace Tec\Base\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderScope implements Scope
{
    private $column;

    private $direction;

    public function __construct($column, $direction = 'asc')
    {
        $this->column = $column;
        $this->direction = $direction;
    }



    public function apply(Builder $builder, Model $model)
    {
          $filables = $model->getFillable();
        if(in_array($this->column,$filables)){
            $column=(strpos($this->column, '.') ==false)?$model->getTable().'.'.$this->column:$this->column;
            $builder->orderBy($column, $this->direction);
        }
    }

}
