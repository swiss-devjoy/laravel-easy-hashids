<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Author;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Book;

beforeEach(function () {
    \DB::enableQueryLog();
});

it('will fetch a related model using findByHashid', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);
    $book = Book::create(['id' => 15, 'title' => 'My First Book']);
    $author->books()->attach($book, ['role' => 'Author', 'sorting' => 1]);

    $firstBook = $author->books()->findByHashid('7PYT0eUGzn');

    expect($firstBook->title)->toBe('My First Book');
    expect($firstBook->pivot->role)->toBe('Author');
    expect(\DB::getQueryLog())->toHaveCount(4);
});

it('will attempt to fetch a model using findByHashid', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);

    $firstBook = $author->books()->findByHashid('7PYT0eUGzn');

    expect($firstBook)->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(2);
});

it('won\'t fetch a model using findByHashid if the hashid cannot be resolved properly', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);

    $firstBook = $author->books()->findByHashid('unknown-hashid');

    expect($firstBook)->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(1);
});

it('will fetch a model using findByHashidOrFail', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);
    $book = Book::create(['id' => 15, 'title' => 'My First Book']);
    $author->books()->attach($book, ['role' => 'Author', 'sorting' => 1]);

    $firstBook = $author->books()->findByHashidOrFail('7PYT0eUGzn');

    expect($firstBook->title)->toBe('My First Book');
    expect($firstBook->pivot->role)->toBe('Author');
    expect(\DB::getQueryLog())->toHaveCount(4);
});

it('will attempt to fetch a model using findByHashidOrFail', function () {
    try {
        $author = Author::create(['id' => 10, 'name' => 'John Doe']);

        $author->books()->findByHashidOrFail('7PYT0eUGzn');
    } catch (ModelNotFoundException $e) {
        expect($e->getMessage())->toContain('No query results for model');
    } finally {
        expect(\DB::getQueryLog())->toHaveCount(2);
    }
});

it('won\'t fetch a model using findByHashidOrFail if the hashid cannot be resolved properly', function () {
    try {
        $author = Author::create(['id' => 10, 'name' => 'John Doe']);

        $author->books()->findByHashidOrFail('unknown-hashid');
    } catch (ModelNotFoundException $e) {
        expect($e->getMessage())->toContain('Hashid cannot be resolved');
    } finally {
        expect(\DB::getQueryLog())->toHaveCount(1);
    }
});

it('will fetch a model using byHashid', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);
    $book = Book::create(['id' => 15, 'title' => 'My First Book']);
    $author->books()->attach($book, ['role' => 'Author', 'sorting' => 1]);

    $firstBook = $author->books()->byHashid('7PYT0eUGzn')->first();

    expect($firstBook->title)->toBe('My First Book');
    expect($firstBook->pivot->role)->toBe('Author');
    expect(\DB::getQueryLog())->toHaveCount(4);
});

it('will attempt to fetch a model using byHashid', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);

    $firstBook = $author->books()->byHashid('7PYT0eUGzn')->first();

    expect($firstBook)->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(2);
});

it('won\'t fetch a model using byHashid if the hashid cannot be resolved properly', function () {
    $author = Author::create(['id' => 10, 'name' => 'John Doe']);

    $firstBook = $author->books()->byHashid('unknown-hashid')->first();

    expect($firstBook)->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(2);
});
