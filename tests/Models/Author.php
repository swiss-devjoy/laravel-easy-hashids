<?php

namespace SwissDevjoy\LaravelEasyHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SwissDevjoy\LaravelEasyHashids\HasHashid;
use SwissDevjoy\LaravelEasyHashids\HashidRouting;

class Author extends Model
{
    use HasHashid;
    use HashidRouting;

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)->withPivot(['role', 'sorting']);
    }
}
