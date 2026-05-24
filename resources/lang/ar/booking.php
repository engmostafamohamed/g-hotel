<?php

return [
    'total_nights_fetched' => 'تم جلب إجمالي عدد الليالي بنجاح.',
    'guest_not_found' => 'لم يتم العثور على النزيل المحدد.',
    'unexpected_fetch' => 'حدث خطأ غير متوقع أثناء جلب إجمالي عدد الليالي.',

    'hotel_id_missing' => 'بيانات الفندق غير محددة. يرجى تحديد فندق صالح.',
    'hotel_id_mismatch' => 'تم اكتشاف عدم تطابق في بيانات الفندق.',
    'room_type_hotel_mismatch' => 'نوع الغرفة المحدد لا ينتمي إلى هذا الفندق.',
    'employee_not_authenticated' => 'يجب أن يكون الموظف مسجلاً للدخول لتنفيذ هذا الإجراء.',

    'no_available_rooms' => 'لا توجد غرف متاحة في التواريخ المحددة.',
    'booking_unsuccessful' => 'تعذر إتمام الحجز لأن جميع أنواع الغرف المطلوبة غير متاحة.',
    'booking_created_successfully' => 'تم إنشاء الحجز بنجاح.',
    'error_happened' => 'حدث خطأ أثناء إنشاء الحجز.',

    'summary' => [
        'booked' => 'الغرف المحجوزة',
        'unavailable' => 'الغرف غير المتاحة',
        'total_price' => 'إجمالي السعر',
        'total_nights' => 'إجمالي الليالي',
    ],

    'validation' => [
        'room_types.required' => 'قائمة أنواع الغرف المطلوبة مفقودة.',
        'room_types.array'    => 'يجب أن تكون قائمة أنواع الغرف في هيئة مصفوفة.',
        'room_types.*.id.required' => 'معرّف نوع الغرفة مطلوب لكل نوع غرفة.',
        'room_types.*.id.exists'   => 'نوع الغرفة المحدد غير موجود.',
        'room_types.*.count.required' => 'عدد الغرف مطلوب لكل نوع غرفة.',
        'room_types.*.count.min'      => 'يجب أن يكون هناك على الأقل غرفة واحدة لكل نوع غرفة.',
    ],
];
