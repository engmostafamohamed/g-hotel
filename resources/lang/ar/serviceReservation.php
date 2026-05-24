<?php

return [

    // ================= General & Business Logic Messages =================
    'fetched'                   => 'تم جلب الحجوزات بنجاح.',
    'fetched_single'            => 'تم جلب الحجز بنجاح.',
    'created'                   => 'تم إنشاء الحجز بنجاح.',
    'updated'                   => 'تم تحديث الحجز بنجاح.',
    'deleted'                   => 'تم حذف الحجز بنجاح.',
    'not_found'                 => 'الحجز غير موجود.',
    'unauthorized'              => 'غير مصرح.',
    'validation_failed'         => 'حدث خطأ في التحقق من البيانات.',
    'unexpected_fetch'          => 'حدث خطأ غير متوقع أثناء جلب الحجوزات.',
    'unexpected_create'         => 'فشل في إنشاء الحجز.',
    'unexpected_update'         => 'فشل في تحديث الحجز.',
    'unexpected_delete'         => 'حدث خطأ أثناء حذف الحجز.',
    'guests_fetched'            => 'تم جلب النزلاء مع الحجوزات بنجاح.',
    'service_category_not_found'=> 'لم يتم العثور على فئة الخدمة المحددة.',
    'unauthorized_hotel_filter' => 'غير مسموح لك بتصفية البيانات باستخدام هذا الفندق.',

    // ================= Business Rule / Service Layer Messages =================
    'datetime_required'         => 'التاريخ ووقت البداية والنهاية مطلوبة للخدمات المجدولة.',
    'schedule_unavailable'      => 'هذه الخدمة غير متاحة في اليوم المحدد.',
    'exception_unavailable'     => 'الخدمة غير متاحة في الوقت المحدد بسبب استثناء.',
    'invalid_time_slot'         => 'الفترة الزمنية المحددة غير صالحة لهذه الخدمة.',
    'fully_booked'              => 'هذه الفترة الزمنية محجوزة بالكامل.',
    'cancellation_mismatch'     => 'يمكن إدخال سبب الإلغاء فقط إذا كانت الحالة "ملغاة".',
    'not_allowed_modify'        => 'غير مسموح لك بتعديل هذا الحجز.',

    // ================= Validation Messages =================
    'validation' => [
        'service_id.required'        => 'الخدمة مطلوبة.',
        'service_id.exists'          => 'الخدمة المحددة غير موجودة.',
        'guest_id.required'          => 'الضيف مطلوب.',
        'guest_id.exists'            => 'الضيف المحدد غير موجود.',
        'date.required'              => 'التاريخ مطلوب لهذه الخدمة.',
        'date.date'                  => 'يجب أن يكون التاريخ صالحًا.',
        'date.after_or_equal'        => 'يجب أن يكون التاريخ اليوم أو بعده.',
        'from.required'              => 'وقت البداية مطلوب لهذه الخدمة.',
        'from.date_format'           => 'يجب أن يكون وقت البداية بتنسيق H:i.',
        'to.required'                => 'وقت النهاية مطلوب لهذه الخدمة.',
        'to.date_format'             => 'يجب أن يكون وقت النهاية بتنسيق H:i.',
        'to.after'                   => 'يجب أن يكون وقت النهاية بعد وقت البداية.',
        'to.after_now'               => 'يجب أن يكون وقت النهاية بعد الوقت الحالي عندما يكون التاريخ هو اليوم.',
        'notes.string'               => 'يجب أن تكون الملاحظات نصًا.',
        'status.in'                  => 'يجب أن تكون الحالة واحدة من: قيد الانتظار، مؤكد، ملغي، مكتمل.',
        'cancellation_reason.string' => 'يجب أن يكون سبب الإلغاء نصًا.',
    ],
];