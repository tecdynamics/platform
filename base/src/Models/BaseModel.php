<?php

namespace Tec\Base\Models;

use Tec\Base\Contracts\BaseModel as BaseModelContract;
use Tec\Base\Facades\MacroableModels;
use Tec\Base\Models\Concerns\HasBaseEloquentBuilder;
use Tec\Base\Models\Concerns\HasMetadata;
use Tec\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static \Tec\Base\Models\BaseQueryBuilder query()
 */
class BaseModel extends Model implements BaseModelContract
{
    use HasBaseEloquentBuilder;
    use HasMetadata;
    use HasUuidsOrIntegerIds;

    public function __get($key)
    {
        if (MacroableModels::modelHasMacro($this::class, $method = 'get' . Str::studly($key) . 'Attribute')) {
            return call_user_func([$this, $method]);
        }

        return parent::__get($key);
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new BaseQueryBuilder($query);
    }

    public function initializeFilable($field) {
        if (!in_array($field,$this->fillable)){
            $this->fillable[] = $field;
        }

    }
    public function disableTimestamps() {
        $this->timestamps=false;

    }
}
