<?php

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SwissDevjoy\LaravelEasyHashids\Tests\Models\Author;

beforeEach(function () {
    Route::get('/simple-routing-test/{author}', RoutingTestComponent::class)->name('routing-test');
});

it('creates the route url with the hashid in place', function () {
    $author = Author::create(['id' => 123]);

    expect(route('routing-test', ['author' => $author], false))->toBe('/simple-routing-test/JbBl4ru0zq');
});

it('resolves the route to a hashided model correctly', function () {
    $author = Author::create(['id' => 123, 'name' => 'John Doe']);

    $response = $this->get(route('routing-test', ['author' => $author]));

    expect($response)
        ->assertSeeText('Author: John Doe')
        ->assertSeeHtml('JbBl4ru0zq')
        ->assertDontSeeHtml('123');
});

#[Layout('layout')]
class RoutingTestComponent extends Component
{
    public Author $author;

    public function render()
    {
        return <<<'HTML'
            <p>Author: {{ $author->name }}</p>
        HTML;
    }
}
