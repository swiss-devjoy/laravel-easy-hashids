<?php

namespace SwissDevjoy\LaravelEasyHashids;

trait HashidRouting
{
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return $query->byHashid($value);
    }

    public function getRouteKey()
    {
        return $this->hashid;
    }

    public function getRouteKeyName()
    {
        return null;
    }
}
