<?php

namespace App\Http\Traits;

use App\Http\Traits\Scopes\ActiveScope;

trait ActiveTrait
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootActiveTrait()
    {
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * Get the name of the "deleted at" column.
     *
     * @return string
     */
    public function getStatusColumn()
    {
        return defined('static::STATUS') ? static::STATUS : 'status';
    }

    /**
     * Get the fully qualified "deleted at" column.
     *
     * @return string
     */
    public function getQualifiedStatusColumn()
    {
        return $this->qualifyColumn($this->getStatusColumn());
    }
}
