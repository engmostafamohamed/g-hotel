<?php

return [
    'fetched'            => 'Roles fetched successfully.',
    'fetched_single'     => 'Role fetched successfully.',
    'created'            => 'Role created successfully.',
    'updated'            => 'Role updated successfully.',
    'deleted'            => 'Role deleted successfully.',
    'not_found'          => 'Role not found.',
    'unexpected'         => 'An unexpected error occurred.',
    'unexpected_create'  => 'An unexpected error occurred while creating the role.',
    'unexpected_update'  => 'An unexpected error occurred while updating the role.',
    'unexpected_delete'  => 'An unexpected error occurred while deleting the role.',

    'validation' => [
        'name.required'       => 'The role name is required.',
        'name.string'         => 'The role name must be a string.',
        'name.unique'         => 'The role name has already been taken.',
        'permissions.array'   => 'Permissions must be an array.',
        'permissions.*.string'=> 'Each permission must be a string.',
        'permissions.*.exists'=> 'One or more permissions do not exist.',
    ],
];
