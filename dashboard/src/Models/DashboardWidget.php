<?php

namespace Tec\Dashboard\Models;

use Tec\Base\Casts\SafeContent;
use Tec\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DashboardWidget extends BaseModel
{
    protected $table = 'dashboard_widgets';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(fn (DashboardWidget $widget) => $widget->settings()->delete());
    }

    public function settings(): HasMany
    {
        return $this->hasMany(DashboardWidgetSetting::class, 'widget_id', 'id');
    }
}
