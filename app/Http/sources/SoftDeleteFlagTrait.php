<?php

namespace App\Http\sources;

use Tsyama\LaravelSoftDeleteFlag\Traits\SoftDeleteFlagTrait as TsyamaSoftDeleteFlagTrait;

trait SoftDeleteFlagTrait
{
    use TsyamaSoftDeleteFlagTrait;

    /**
     * Determine if the model instance has been soft-deleted.
     *
     * @return bool
     */
    public function trashed()
    {
        return (bool) $this->{$this->getDeletedAtColumn()};
    }

}