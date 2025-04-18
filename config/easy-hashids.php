<?php

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
