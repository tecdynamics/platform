<?php

namespace Tec\Base\Models;

use Tec\Base\Contracts\BaseModel as BaseModelContract;
use Illuminate\Database\Eloquent\Model;
use Tec\Base\Models\Concerns\HasBaseEloquentBuilder;
use Tec\Base\Models\Concerns\HasMetadata;
use Tec\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Illuminate\Support\Str;
use MacroableModels;

class BaseModel extends Model implements BaseModelContract
{
    use  HasBaseEloquentBuilder;
    use  HasMetadata;
    use  HasUuidsOrIntegerIds;

    public function __get($key)
    {
        if (MacroableModels::modelHasMacro($this::class, $method = 'get' . Str::studly($key) . 'Attribute')) {
            return call_user_func([$this, $method]);
        }

        return parent::__get($key);
    }
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
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
