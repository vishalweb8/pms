<?php

namespace App\Http\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var string[]
     */
    protected $extensions = ['WithBlocked', 'WithoutBlocked', 'OnlyBlocked'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where($model->getQualifiedStatusColumn(), 1);
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithBlocked(Builder $builder)
    {
        $builder->macro('withBlocked', function (Builder $builder, $withBlocked = true) {
            if (! $withBlocked) {
                return $builder->withoutBlocked();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutBlocked(Builder $builder)
    {
        $builder->macro('withoutBlocked', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where($model->getQualifiedDeletedAtColumn(), 1);

            return $builder;
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyBlocked(Builder $builder)
    {
        $builder->macro('onlyBlocked', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where($model->getQualifiedDeletedAtColumn(),0);

            return $builder;
        });
    }
}
