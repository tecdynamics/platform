<?php

namespace Tec\Base\Models;

use Illuminate\Database\Eloquent\Builder;
use Tec\Base\Enums\BaseStatusEnum;

class BaseQueryBuilder extends Builder
{
    /**
     * @param string $column
     * @param string|null $term
     * @return BaseQueryBuilder
     */
    public function addSearch(string $column, string|null $term, bool $isPartial = true, bool $or = true): static
    {
        if (! $isPartial) {
            $this->{$or ? 'orWhere' : 'where'}($column, 'LIKE', '%' . trim($term) . '%');

            return $this;
        }

        $searchTerms = explode(' ', $term);

        $sql = 'LOWER(' . $this->getGrammar()->wrap($column) . ') LIKE ? ESCAPE ?';

        foreach ($searchTerms as $searchTerm) {
            $searchTerm = mb_strtolower($searchTerm, 'UTF8');
            $searchTerm = str_replace('\\', $this->getBackslashByPdo(), $searchTerm);
            $searchTerm = addcslashes($searchTerm, '%_');

            $this->orWhereRaw($sql, ['%' . $searchTerm . '%', '\\']);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getBackslashByPdo()
    {
        if (config('database.default') === 'sqlite') {
            return '\\\\';
        }

        return '\\\\\\';
    }
    public function wherePublished($column = 'status'): static
    {
        $this->where($column, BaseStatusEnum::PUBLISHED);

        return $this;
    }

    public function get($columns = ['*'])
    {
        $data = parent::get($columns);

        return apply_filters('model_after_execute_get', $data, $this->getModel(), $columns);
    }
}
