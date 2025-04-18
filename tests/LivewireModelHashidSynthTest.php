<?php

use Livewire\Component;
use Livewire\Livewire;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Book;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Chapter;

it('will use hashids in livewire if hashid trait is included', function () {
    $book = Book::create(['id' => 10]);
    $chapter = $book->chapters()->create(['id' => 15]);

    $component = Livewire::test(ComponentWithPublicEloquentModels::class, [
        'book' => $book,
        'chapter' => $chapter,
    ]);

    $component
        ->assertSeeHtml('<p>book id: 10</p>')
        ->assertSeeHtml('<p>book hashid: 2tFub5I1ge</p>')
        ->assertSeeHtml('<p>chapter id: 15</p>')
        ->assertSeeHtml('<p>chapter hashid: </p>');

    expect(data_get($component->snapshot, 'data.book.1'))->toBe([
        'class' => Book::class,
        'key' => '2tFub5I1ge',
        's' => 'hmdl',
    ]);

    expect(data_get($component->snapshot, 'data.chapter.1'))->toBe([
        'class' => Chapter::class,
        'key' => 15,
        's' => 'mdl',
    ]);

    // when refreshing, models will be hydrated, hashids decoded and models fetched from database
    $component->refresh();

    $component
        ->assertSeeHtml('<p>book id: 10</p>')
        ->assertSeeHtml('<p>book hashid: 2tFub5I1ge</p>')
        ->assertSeeHtml('<p>chapter id: 15</p>')
        ->assertSeeHtml('<p>chapter hashid: </p>');
});

class ComponentWithPublicEloquentModels extends Component
{
    public Book $book;

    public Chapter $chapter;

    public function render()
    {
        return <<<'HTML'
            <div>
                <p>book id: {{ $book->id }}</p>
                <p>book hashid: {{ $book->hashid }}</p>
                <p>chapter id: {{ $chapter->id }}</p>
                <p>chapter hashid: {{ $chapter->hashid }}</p>
            </div>
        HTML;
    }
}
