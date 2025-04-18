<?php

namespace SwissDevjoy\LaravelEasyHashids\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SwissDevjoy\LaravelEasyHashids\HasHashid;
use SwissDevjoy\LaravelEasyHashids\HashidRouting;

class Book extends Model
{
    use HasHashid;
    use HashidRouting;

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class)->withPivot(['role', 'sorting'])->orderByPivot('sorting');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('sorting');
    }
}
