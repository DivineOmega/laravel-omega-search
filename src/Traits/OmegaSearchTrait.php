<?php

namespace DivineOmega\LaravelOmegaSearch\Traits;

use DivineOmega\OmegaSearch\OmegaSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait OmegaSearchTrait
{
    /**
     * Perform an intelligent fuzzy search, and returns a query builder filtered
     * to the related records in descending order of relevance.
     *
     * @param $searchText
     *
     * @return Builder
     */
    public static function omegaSearch($searchText)
    {
        /** @var Model $model */
        $model = new self();
        $keyName = $model->getKeyName();

        $search = (new OmegaSearch())
            ->setDatabaseConnection(DB::getPdo())
            ->setTable($model->getTable())
            ->setPrimaryKey($keyName)
            ->setFieldsToSearch($model->getOmegaSearchFieldsToSearch())
            ->setConditions($model->getOmegaSearchConditions());

        $results = $search->query($searchText, 100);

        $ids = array_map(function ($result) {
            return $result->id;
        }, $results->results);

        $products = self::query()
            ->whereIn($keyName, $ids)
            ->orderByRaw(DB::raw('FIELD('.$keyName.', '.implode(',', $ids).')'));

        return $products;
    }

    /**
     * Must return an array of the model's fields to search.
     *
     * @return array
     */
    abstract public function getOmegaSearchFieldsToSearch();

    /**
     * Must return an associative array of the search conditions.
     *
     * e.g ['active' => 1, 'discontinued' => 0]
     *
     * @return array
     */
    abstract public function getOmegaSearchConditions();
}
