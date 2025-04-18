# Easy HashIds for Laravel Eloquent Models with Livewire Support

[![Latest Version on Packagist](https://img.shields.io/packagist/v/swiss-devjoy/laravel-easy-hashids.svg?style=flat-square)](https://packagist.org/packages/swiss-devjoy/laravel-easy-hashids)
[![Total Downloads](https://img.shields.io/packagist/dt/swiss-devjoy/laravel-easy-hashids.svg?style=flat-square)](https://packagist.org/packages/swiss-devjoy/laravel-easy-hashids)

A lightweight package that adds Hashid support to your Eloquent models. It automatically generates unique hashids for your models and includes Livewire support for only exposing the hashid as key to properly identify a public model.

## Why Use HashIds?

- **Security by Obscurity**: Hide your sequential database IDs from users (although is not an encryption library)
- **Predictability Prevention**: Avoid exposing information about your data volume
- **Performance**: Invalid hashids don't lead to unnecessary database fetches (in most cases)

## Features

- ✅ Easy integration with any Eloquent model
- ✅ Automatic hashid generation based on model IDs
- ✅ Livewire component support for passing models with hashids
- ✅ Route model binding support
- ✅ Auto generation of different hashids for different models, even if the ID is the same
- ✅ Configurable alphabet and minimum length globally and per model
- ✅ No database migrations needed - works with your existing models

## Installation

You can install the package via composer:

```bash
composer require swiss-devjoy/laravel-easy-hashids
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-easy-hashids-config"
```

This is the contents of the published config file:

```php
return [
    'default' => [
        // Generate a unique alphabet here: https://sqids.org/playground
        'alphabet' => env('HASHID_DEFAULT_ALPHABET', 'VCzODgjZNMFaXTfqnhLp84EtHlk7RmiWrScBoPIwK2QGxs1ed35UJ6yAYb0v9u'),
        'min_length' => env('HASHID_DEFAULT_MIN_LENGTH', 10),
    ],

    'models' => [
        // App\Models\YourModel::class => [
        //     'alphabet' => 'kwevdSQOEiT349X5atVrLozGHFWYp87uAUlc0mbPNIJKf1qMshCyg2BD6ZxnjR',
        //     'min_length' => 10,
        // ],
    ],
];
```

## Usage

1. Add the `HasHashid` and `HashidRouting` traits to any Eloquent model:

```php
use SwissDevjoy\LaravelEasyHashids\HasHashid;
use SwissDevjoy\LaravelEasyHashids\HashidRouting;

class Product extends Model
{
    use HasHashid;
    use HashidRouting;
}
```

2. Access the hashid in your code:

```php
$product = Product::find(1);
echo $product->hashid; // Returns something like "2tFub5I1ge"
```

3. Use route model binding with hashids:

```php
Route::get('/products/{product}', function (Product $product) {
    return view('products.show', compact('product'));
})->name('products.product');

// Generates a URL with the hashid that looks sth like /products/2tFub5I1ge
route('products.show', ['product' => $product]);
```

## Configuration

The published config file contains these settings:

```php
return [
    'default' => [
        // Generate a unique alphabet here: https://sqids.org/playground
        'alphabet' => env('HASHID_DEFAULT_ALPHABET', 'VCzODgjZNMFaXTfqnhLp84EtHlk7RmiWrScBoPIwK2QGxs1ed35UJ6yAYb0v9u'),
        'min_length' => env('HASHID_DEFAULT_MIN_LENGTH', 10),
    ],

    'models' => [
        // App\Models\YourModel::class => [
        //     'alphabet' => 'kwevdSQOEiT349X5atVrLozGHFWYp87uAUlc0mbPNIJKf1qMshCyg2BD6ZxnjR',
        //     'min_length' => 10,
        // ],
    ],
];
```

### Working with Livewire

The package automatically handles Livewire integration. When passing models with the `HasHashid` trait to Livewire components, their IDs will be converted to hashids:

```php
class BookComponent extends Component
{
    public Book $book;

    public function render()
    {
        return view('components.book');
    }
}
```

The model will be automatically serialized with the hashid and reconstituted when needed.

### Converting Between IDs and HashIds

You can manually convert between IDs and hashids:

```php
$book = Book::make();

// Convert ID to hashid
$hashid = $book->idToHashid(10);

// Convert hashid back to ID
$id = $book->hashidToId('2tFub5I1ge');
```

### Relationships

The package works seamlessly with Eloquent relationships:

```php
// Author model (uses HasHashid)
$author = Author::findByHashidOrFail('uwe14hrgh');

// Book model (uses HasHashid)
$book = $author->books()->findByHashid('2tFub5I1ge');
```

## Advanced Configuration

### Model-Specific Settings

You can customize the alphabet and minimum hashid length for specific models in the config file:

```php
'models' => [
    App\Models\Book::class => [
        'alphabet' => 'xyz123ABC789DEFGHIJKLMNOPQRSTUVWdefghijklmnopqrstuvwab456XYZ',
        'min_length' => 12,
    ],
],
```

### Auto-Generated Alphabets

If no custom configuration is provided, the package will generate a unique alphabet for each model based on the class name. This ensures distinct hashids across different models even for identical database IDs.

For example, `User::find(1)->hashid` will be different from `Product::find(1)->hashid`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dimitri König](https://github.com/dimitri-koenig)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
