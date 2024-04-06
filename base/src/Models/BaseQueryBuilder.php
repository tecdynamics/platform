<?php

namespace Tec\Base\Models;

use Tec\Base\Enums\BaseStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BaseQueryBuilder extends Builder
{
    /**
     * @Function   convertAccentLetters
     * @param $text
     * @Author    : Michail Fragkiskos
     * @Created at: 27/03/2024 at 09:57
     * @param $text
     * @return string
     */
    function convertAccentLetters($text) {
        $charMap = [
            'ά' => 'α',
            'ί' => 'ι',
            'ό' => 'ο',
            'έ' => 'ε',
            'ύ' => 'υ',
            'ώ' => 'ω',
            '.' => '',
            ',' => '',
        ];
        $text = strtr(strtolower($text), $charMap);

        return $text;
    }
    /**
     * @Function   removeAccentLetters
     * @param $column
     * @Author    : Michail Fragkiskos
     * @Created at: 27/03/2024 at 09:57
     * @param $column
     * @return string
     */
    public function removeAccentLetters ($column) {
        return $column;
        return "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE($column,'ά','α'),'ί','i'),'ό','o'),'έ',''),'ύ',''),'ώ','')";
    }

    public function addSearch(string $column, string|null $term, bool $isPartial = true, bool $or = true): static
    {

        if (! $isPartial) {
            $this->{$or ? 'orWhere' : 'where'}($this->removeAccentLetters($column), 'LIKE', '%' . $this->convertAccentLetters(trim($term)) . '%');

            return $this;
        }

        $searchTerms = explode(' ', $term);

        $sql = 'LOWER(' .$this->removeAccentLetters($this->getGrammar()->wrap($column)) . ') LIKE ? ESCAPE ?';

        foreach ($searchTerms as $searchTerm) {
            $searchTerm = mb_strtolower($searchTerm, 'UTF8');
            $searchTerm = str_replace('\\', $this->getBackslashByPdo(), $searchTerm);
            $searchTerm = $this->convertAccentLetters($searchTerm);
            $searchTerm = addcslashes($searchTerm, '%_');

            $this->orWhereRaw($sql, ['%' . $searchTerm . '%', '\\']);
        }
        return $this;
    }

    protected function getBackslashByPdo(): string
    {
        if (DB::getDefaultConnection() === 'sqlite') {
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
