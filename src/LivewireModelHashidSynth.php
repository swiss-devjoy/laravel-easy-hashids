<?php

declare(strict_types=1);

namespace SwissDevjoy\LaravelEasyHashids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Features\SupportModels\ModelSynth;

class LivewireModelHashidSynth extends ModelSynth
{
    public static $key = 'hmdl';

    public static function match($target): bool
    {
        return $target instanceof Model && method_exists($target, 'hashid');
    }

    public function dehydrate($target): array
    {
        $content = parent::dehydrate($target);

        if ($target->exists && ! empty($content[1]['key']) && ($hashid = $target->hashid)) {
            $content[1]['key'] = $hashid;
        }

        return $content;
    }

    public function hydrate($data, $meta): Model
    {
        if (! empty($meta['class']) && ! empty($meta['key'])) {
            $class = $meta['class'];

            // If no alias found, this returns `null`
            $aliasClass = Relation::getMorphedModel($class);

            if (! is_null($aliasClass)) {
                $class = $aliasClass;
            }

            if ($modelId = (int) (new $class)->make()->hashidToId($meta['key'])) {
                $meta['key'] = $modelId;
            }
        }

        return parent::hydrate($data, $meta);
    }
}
