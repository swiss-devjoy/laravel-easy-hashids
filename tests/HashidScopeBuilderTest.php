<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Author;

beforeEach(function () {
    \DB::enableQueryLog();
});

it('will fetch a model using findByHashid', function () {
    Author::create(['id' => 10, 'name' => 'John Doe']);

    expect(Author::findByHashid('h28Nm4I0OK')->name)->toBe('John Doe');
    expect(\DB::getQueryLog())->toHaveCount(2);
});

it('will attempt to fetch a model using findByHashid', function () {
    expect(Author::findByHashid('h28Nm4I0OK'))->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(1);
});

it('won\'t fetch a model using findByHashid if the hashid cannot be resolved properly', function () {
    expect(Author::findByHashid('unknown-hashid'))->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(0);
});

it('will fetch a model using findByHashidOrFail', function () {
    Author::create(['id' => 10, 'name' => 'John Doe']);

    expect(Author::findByHashidOrFail('h28Nm4I0OK')->name)->toBe('John Doe');
    expect(\DB::getQueryLog())->toHaveCount(2);
});

it('will attempt to fetch a model using findByHashidOrFail', function () {
    try {
        expect(Author::findByHashidOrFail('h28Nm4I0O'));
    } catch (ModelNotFoundException $e) {
        expect($e->getMessage())->toContain('No query results for model');
    } finally {
        expect(\DB::getQueryLog())->toHaveCount(1);
    }
});

it('won\'t fetch a model using findByHashidOrFail if the hashid cannot be resolved properly', function () {
    try {
        expect(Author::findByHashidOrFail('unknown-hashid'));
    } catch (ModelNotFoundException $e) {
        expect($e->getMessage())->toContain('Hashid cannot be resolved');
    } finally {
        expect(\DB::getQueryLog())->toHaveCount(0);
    }
});

it('will fetch a model using byHashid', function () {
    Author::create(['id' => 10, 'name' => 'John Doe']);

    expect(Author::byHashid('h28Nm4I0OK')->first()->name)->toBe('John Doe');
    expect(\DB::getQueryLog())->toHaveCount(2);
});

it('will attempt to fetch a model using byHashid', function () {
    expect(Author::byHashid('h28Nm4I0OK')->first())->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(1);
});

it('won\'t fetch a model using byHashid if the hashid cannot be resolved properly', function () {
    expect(Author::byHashid('unknown-hashid')->first())->toBe(null);
    expect(\DB::getQueryLog())->toHaveCount(1);
});
