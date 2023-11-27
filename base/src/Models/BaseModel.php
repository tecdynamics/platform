<?php

namespace Tec\Base\Models;

use Tec\Base\Models\Concerns\HasBaseEloquentBuilder;
use Tec\Base\Models\Concerns\HasMetadata;
use Tec\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Eloquent;
use Illuminate\Support\Str;
use MacroableModels;

class BaseModel extends Eloquent
{
    use HasBaseEloquentBuilder;
    use HasMetadata;
    use HasUuidsOrIntegerIds;
    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (class_exists('MacroableModels')) {
            $method = 'get' . Str::studly($key) . 'Attribute';
            if (MacroableModels::modelHasMacro(get_class($this), $method)) {
                return call_user_func([$this, $method]);
            }
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
