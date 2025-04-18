<?php

namespace SwissDevjoy\LaravelEasyHashids;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Scope;

class HashidScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        //
    }

    public function extend(Builder $builder)
    {
        $builder->macro('findByHashid', function (Builder $builder, $hashid) {
            $id = $hashid ? $builder->getModel()->hashidToId($hashid) : null;

            return $id ? $builder->find($id) : null;
        });

        $builder->macro('findByHashidOrFail', function (Builder $builder, $hashid) {
            $id = $hashid ? $builder->getModel()->hashidToId($hashid) : null;

            throw_if(! $id, ModelNotFoundException::class, 'Hashid cannot be resolved');

            return $builder->findOrFail($id);
        });

        $builder->macro('byHashid', function (Builder $builder, $hashid) {
            $model = $builder->getModel();

            return $builder->where(
                $model->qualifyColumn($model->getKeyName()),
                $model->hashidToId($hashid)
            );
        });

        /**
         * The following macros are for relations to properly resolve data with pivot data.
         *
         * Without this dedicated Relation macros, the pivot data will merged with the model data
         * and is not accessible as a separate "pivot" property as it should be.
         *
         * As seen in the tests, an example of this is:
         * PHP: $firstBook = $author->books()->findByHashid('7PYT0eUGzn');
         * SQL: select
         *   *
         * from
         *   "books"
         *   inner join "author_book" on "books"."id" = "author_book"."book_id"
         * where
         *   "author_book"."author_id" = 10
         *   and "books"."id" = 15
         * limit
         *   1
         *
         * The correct SQL should be:
         * select "books".*,
         *   "author_book"."author_id" as "pivot_author_id",
         *   "author_book"."book_id" as "pivot_book_id",
         *   "author_book"."role" as "pivot_role",
         *   "author_book"."sorting" as "pivot_sorting"
         * from
         *  "books"
         *   inner join "author_book" on "books"."id" = "author_book"."book_id"
         * where
         *   "author_book"."author_id" = 10
         *   and "books"."id" = 15
         * limit
         *   1
         *
         * That way the pivot data will be accessible as a separate "pivot" property as it should be.
         */
        Relation::macro('findByHashid', function ($hashid) {
            $id = $hashid ? $this->getModel()->hashidToId($hashid) : null;

            return $id ? $this->find($id) : null;
        });

        Relation::macro('findByHashidOrFail', function ($hashid) {
            $id = $hashid ? $this->getModel()->hashidToId($hashid) : null;

            throw_if(! $id, ModelNotFoundException::class, 'Hashid cannot be resolved');

            return $this->findOrFail($id);
        });
    }
}
