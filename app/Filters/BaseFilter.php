<?php

namespace App\Filters;

use App\Filters\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;
use Str;

/**
 * Class BaseFilter.
 *
 * @package App\Filters
 */
abstract class BaseFilter
{
    public static function applyFromParams($params, Builder $query)
    {
        foreach ($params as $filterName => $value) {
            /** @var $decorator Filter */
            $decorator = static::createFilterDecorator($filterName);
            if (static::isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }
        }
        return $query;
    }

    abstract public static function filterNamespace();

    protected static function createFilterDecorator($name)
    {
        return static::filterNamespace() . '\\' . Str::studly($name);
    }

    protected static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }
}
