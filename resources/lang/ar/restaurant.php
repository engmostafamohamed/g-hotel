<?php
return [
    'schedules_array' => 'يجب أن يكون جدول المواعيد مصفوفة.',
    'schedule_day_required' => 'حقل اليوم مطلوب لكل جدول.',
    'schedule_day_string' => 'يجب أن يكون اليوم نصًا صالحًا.',
    'schedule_day_in' => 'يجب أن يكون اليوم أحد أيام الأسبوع من السبت إلى الجمعة.',

    'schedule_opening_required' => 'وقت الفتح مطلوب عند إدخال جدول المواعيد.',
    'schedule_opening_format' => 'يجب أن يكون وقت الفتح بالصيغة HH:MM (مثال: 09:00).',

    'schedule_closing_required' => 'وقت الإغلاق مطلوب عند إدخال جدول المواعيد.',
    'schedule_closing_format' => 'يجب أن يكون وقت الإغلاق بالصيغة HH:MM (مثال: 17:00).',
    'schedule_closing_after_opening' => 'يجب أن يكون وقت الإغلاق بعد وقت الفتح.',

    'hotel_id_required' => 'رقم الفندق مطلوب.',
    'hotel_id_integer' => 'رقم الفندق يجب أن يكون رقماً صحيحاً.',
    'hotel_id_not_found' => 'الفندق المحدد غير موجود.',

    'restaurant_id_required' => 'رقم المطعم مطلوب.',
    'restaurant_id_integer' => 'رقم المطعم يجب أن يكون رقماً صحيحاً.',
    'restaurant_id_not_found' => 'المطعم المحدد غير موجود.',
    'no_changes' => 'لايوجدأي تعديلات.',
    'in_dining_must_bool' => 'يجب أن تكون قيمة in_dining إما true أو false.',
    'room_service_must_bool' => 'يجب أن تكون قيمة room_service إما true أو false.',
    'data_added_successfully' => 'تم حفظ البيانات بنجاح .',

    'schedule_day_invalid' => 'يوم الأسبوع المحدد في الجدول غير صالح.',
    'schedule_work_from_required' => 'وقت بداية العمل مطلوب.',
    'schedule_work_from_format' => 'يجب أن يكون وقت بداية العمل بصيغة H:i.',
    'schedule_work_to_required' => 'وقت نهاية العمل مطلوب.',
    'schedule_work_to_format' => 'يجب أن يكون وقت نهاية العمل بصيغة H:i.',
    'schedule_work_to_after' => 'يجب أن يكون وقت نهاية العمل بعد وقت البداية.',

    'exception_date_required' => 'تاريخ الاستثناء مطلوب.',
    'exception_date_format' => 'يجب أن يكون تاريخ الاستثناء بصيغة Y-m-d.',
    'exception_from_required' => 'وقت بداية الاستثناء مطلوب.',
    'exception_from_format' => 'يجب أن يكون وقت بداية الاستثناء بصيغة H:i.',
    'exception_to_required' => 'وقت نهاية الاستثناء مطلوب.',
    'exception_to_format' => 'يجب أن يكون وقت نهاية الاستثناء بصيغة H:i.',

    'exception_from_must_be_before_to' => 'يجب أن يكون وقت بداية الاستثناء قبل وقت النهاية.',
    'work_from_must_be_before_to' => 'يجب أن يكون وقت بداية العمل قبل وقت النهاية.',
    'menu_data_not_found' => 'لم يتم العثور على بيانات المطعم.',
    'menu_fetched_successfully' => 'تم جلب القائمة بنجاح.',

    'fetched'            => 'تم جلب المطاعم بنجاح.',
    'fetched_single'     => 'تم جلب المطعم بنجاح.',
    'created'            => 'تم إنشاء المطعم بنجاح.',
    'updated'            => 'تم تحديث المطعم بنجاح.',
    'deleted'            => 'تم حذف المطعم بنجاح.',
    'not_found'          => 'المطعم غير موجود.',
    'unexpected'         => 'حدث خطأ غير متوقع.',
    'unexpected_create'  => 'حدث خطأ غير متوقع أثناء إنشاء المطعم.',
    'unexpected_update'  => 'حدث خطأ غير متوقع أثناء تحديث المطعم.',
    'unexpected_delete'  => 'حدث خطأ غير متوقع أثناء حذف المطعم.',

    'validation' => [
        'name.en.required' => 'اسم المطعم بالإنجليزية مطلوب.',
        'name.en.unique'   => 'اسم المطعم بالإنجليزية مستخدم بالفعل.',
        'name.ar.required' => 'اسم المطعم بالعربية مطلوب.',
        'name.ar.unique'   => 'اسم المطعم بالعربية مستخدم بالفعل.',
        'cuisine.required'    => 'المطبخ مطلوب.',
        'cuisine.array'       => 'يجب أن يكون المطبخ مصفوفة.',
        'cuisine.en.required' => 'المطبخ بالإنجليزية مطلوب.',
        'cuisine.ar.required' => 'المطبخ بالعربية مطلوب.',
        'hotel_id.required'   => 'الفندق مطلوب.',
        'hotel_id.exists'     => 'الفندق المحدد غير موجود.',
        'image.image'         => 'يجب أن تكون الصورة ملف صورة.',
        'image.max'           => 'لا يجوز أن تكون الصورة أكبر من :max كيلوبايت.',
    ],
];

