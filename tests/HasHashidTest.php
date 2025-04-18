<?php

use SwissDevjoy\LaravelEasyHashids\Tests\Models\Author;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Book;

it('encodes and decodes hashids if called directly', function () {
    $book = Book::create(['id' => 10]);
    $chapter = $book->chapters()->create(['id' => 30]);

    expect($book->hashid)->toBe('2tFub5I1ge');
    expect($book->getRouteKey())->toBe('2tFub5I1ge');
    expect(Book::make()->hashidToId('2tFub5I1ge'))->toBe(10);
    expect(Book::make()->idToHashid(10))->toBe('2tFub5I1ge');

    expect($chapter->hashid)->toBe(null);
    expect($chapter->getRouteKey())->toBe(30);
});

it('will generate a unique hashid for each model instance', function () {
    $author1 = Author::create(['id' => 10]);
    $author2 = Author::create(['id' => 20]);

    expect($author1->hashid)->toBe('h28Nm4I0OK');
    expect($author2->hashid)->toBe('wE94LAOR6E');

    $book1 = Book::create(['id' => 10]);
    $book2 = Book::create(['id' => 20]);

    expect($book1->hashid)->toBe('2tFub5I1ge');
    expect($book2->hashid)->toBe('Z7ufPeGp38');
});

it('will generate a unique hashid with own model config for each model instance', function () {
    $authorClassName = Author::class;
    config([
        "easy-hashids.models.$authorClassName" => [
            'alphabet' => 'YMguR6VXFmjOsKIhqJotPTela0rpdEZix4UbCv7wA81kQ5HDy3LG2cSBNzn9fW',
            'min_length' => 4,
        ],
    ]);

    $author1 = Author::create(['id' => 10]);
    $author2 = Author::create(['id' => 20]);

    expect($author1->hashid)->toBe('jSqR');
    expect($author2->hashid)->toBe('0xgK');

    $book1 = Book::create(['id' => 10]);
    $book2 = Book::create(['id' => 20]);

    expect($book1->hashid)->toBe('2tFub5I1ge');
    expect($book2->hashid)->toBe('Z7ufPeGp38');
});
