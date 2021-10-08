<?php

namespace App\Utopia\Traits;

use App\Utopia\Filters\QueryFilters;

trait Filterable
{
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
