<?php

namespace App\Utopia\Filters;

class ProductFilters extends QueryFilters
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;

        parent::__construct($request);
    }

    public function name($term = null)
    {
        return $this->builder->where('name', 'LIKE', "%$term%");
    }

    public function status(bool $term = null)
    {
        return $this->builder->where('status', $term);
    }
}
