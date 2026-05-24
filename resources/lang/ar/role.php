<?php

return [
    'fetched'            => 'تم جلب الأدوار بنجاح.',
    'fetched_single'     => 'تم جلب الدور بنجاح.',
    'created'            => 'تم إنشاء الدور بنجاح.',
    'updated'            => 'تم تحديث الدور بنجاح.',
    'deleted'            => 'تم حذف الدور بنجاح.',
    'not_found'          => 'الدور غير موجود.',
    'unexpected'         => 'حدث خطأ غير متوقع.',
    'unexpected_create'  => 'حدث خطأ غير متوقع أثناء إنشاء الدور.',
    'unexpected_update'  => 'حدث خطأ غير متوقع أثناء تحديث الدور.',
    'unexpected_delete'  => 'حدث خطأ غير متوقع أثناء حذف الدور.',

    'validation' => [
        'name.required'       => 'اسم الدور مطلوب.',
        'name.string'         => 'يجب أن يكون اسم الدور نصًا.',
        'name.unique'         => 'اسم الدور مستخدم بالفعل.',
        'permissions.array'   => 'يجب أن تكون الصلاحيات على شكل مصفوفة.',
        'permissions.*.string'=> 'يجب أن تكون كل صلاحية نصًا.',
        'permissions.*.exists'=> 'واحدة أو أكثر من الصلاحيات غير موجودة.',
    ],
];