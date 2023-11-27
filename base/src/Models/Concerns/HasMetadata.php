<?php

namespace Tec\Base\Models\Concerns;

use Tec\Base\Facades\MetaBoxFacade as MetaBoxSupport;
use Tec\Base\Models\MetaBox;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMetadata
{
    public function metadata(): MorphMany
    {
        return $this
            ->morphMany(MetaBox::class, 'reference')
            ->select([
                'reference_id',
                'reference_type',
                'meta_key',
                'meta_value',
            ]);
    }

    public function getMetaData(string $key, bool $single = false): array|string|null
    {
        $field = $this->metadata
            ->where('meta_key', apply_filters('stored_meta_box_key', $key, $this))
            ->first();

        if (! $field) {
            $field = $this->metadata->where('meta_key', $key)->first();
        }

        if (! $field) {
            return $single ? '' : [];
        }

        return MetaBoxSupport::getMetaData($field, $key, $single);
    }
}
