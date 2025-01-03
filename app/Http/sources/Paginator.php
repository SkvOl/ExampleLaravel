<?php

namespace App\Http\sources;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\sources\Wrapper;

class Paginator extends LengthAwarePaginator
{
    use Wrapper;
    
    public function transform(callable $callback)
    {
        parent::transform($callback);

        return $this;
    }

    public function map(callable $callback)
    {
        $this->items = $this->items->map($callback);

        return $this;
    }
}