<?php

return [
    'name' => [
        'required' => 'The service category name is required.',
        'unique' => 'This service category name already exists.',
    ],
    'description' => [
        'string' => 'The description must be a valid string.',
    ],
    'type' => [
        'required' => 'The service category type is required.',
        'in' => 'The selected service category type is invalid.',
    ],
    'validation_error' => 'Validation error',
];
