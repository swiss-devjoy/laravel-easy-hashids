<?php

namespace SwissDevjoy\LaravelEasyHashids;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Sqids\Sqids;

trait HasHashid
{
    public static function bootHasHashid()
    {
        static::addGlobalScope(new HashidScope);
    }

    public function hashid(): Attribute
    {
        return Attribute::make(get: fn () => $this->getKey() ? $this->idToHashid($this->getKey()) : null)->shouldCache();
    }

    public function hashidToId(string $hashid)
    {
        $lib = $this->getSqidInstanceForClass(static::class);

        return $lib->decode($hashid)[0] ?? null;
    }

    public function idToHashid(int $id)
    {
        $lib = $this->getSqidInstanceForClass(static::class);

        return $lib->encode([$id]);
    }

    protected function getSqidInstanceForClass($class)
    {
        $containerKey = 'hashidInstances-'.$class;

        if (app()->has($containerKey)) {
            return app($containerKey);
        }

        $defaultAlphabet = config('easy-hashids.default.alphabet', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
        $defaultMinLength = config('easy-hashids.default.min_length', 10);

        $alphabet = config("easy-hashids.models.$class.alphabet");
        $minLength = config("easy-hashids.models.$class.min_length", $defaultMinLength);

        // if no dedicated model alphabet exists, use the default alphabet and shuffle it predictably based on the class name
        if (! $alphabet) {
            $asciiSumOfClassName = array_sum(array_map(fn ($char) => ord($char), str_split($class)));
            mt_srand($asciiSumOfClassName);
            $alphabet = str_shuffle($defaultAlphabet);
        }

        $instance = new Sqids($alphabet, $minLength);

        app()->bind($containerKey, fn () => $instance);

        return $instance;
    }
}
