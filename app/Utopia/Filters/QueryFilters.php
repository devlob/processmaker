<?php

namespace App\Utopia\Filters;

use Illuminate\Database\Eloquent\Builder;

class QueryFilters
{
    protected $request;
    protected $builder;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $key => $value) {
            if (!method_exists($this, $key)) {
                continue;
            }

            if (strlen($value)) {
                $this->$key($value);
            } else {
                $this->$key();
            }
        }

        return $this->builder;
    }

    public function filters()
    {
        return $this->request->all();
    }
}
