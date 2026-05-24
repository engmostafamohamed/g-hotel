<?php

return [
    'created' => 'تم إرسال التقييم بنجاح.',
    'updated' => 'تم تحديث التقييم بنجاح.',
    'deleted' => 'تم حذف التقييم بنجاح.',
    'fetched' => 'تم جلب التقييمات بنجاح.',
    'fetched_single' => 'تم جلب التقييم بنجاح.',
    'unauthorized_hotel_filter' => 'معرّف الفندق المقدم لا يتطابق مع الفندق المصرح لك به.',
    'not_found' => 'لم يتم العثور على التقييم المطلوب.',
    'unexpected_create' => 'حدث خطأ غير متوقع أثناء إنشاء التقييم.',
    'unexpected_update' => 'حدث خطأ غير متوقع أثناء تحديث التقييم.',
    'unexpected_delete' => 'حدث خطأ غير متوقع أثناء حذف التقييم.',
    'unexpected_fetch' => 'حدث خطأ غير متوقع أثناء جلب التقييم.',

    'validation' => [
        'booking_id.required_without' => 'رقم الحجز مطلوب إذا لم يتم إدخال رقم حجز خدمة.',
        'booking_id.exists' => 'الحجز المحدد غير موجود.',
        'booking_id.unique' => 'هذا الحجز يحتوي بالفعل على تقييم.',
        'service_reservation_id.required_without' => 'رقم حجز الخدمة مطلوب إذا لم يتم إدخال رقم الحجز.',
        'service_reservation_id.exists' => 'حجز الخدمة المحدد غير موجود.',
        'service_reservation_id.unique' => 'هذا الحجز يحتوي بالفعل على تقييم.',
        'rating.required' => 'التقييم مطلوب.',
        'rating.integer' => 'يجب أن يكون التقييم عدداً صحيحاً.',
        'rating.min' => 'يجب ألا يقل التقييم عن :min.',
        'rating.max' => 'يجب ألا يزيد التقييم عن :max.',
        'comment.string' => 'يجب أن يكون التعليق نصاً.',
        'comment.max' => 'يجب ألا يزيد التعليق عن :max حرفاً.',
        'booking_not_owned' => 'لا يمكنك ترك تقييم لحجز ليس لك.',
        'booking_wrong_hotel' => 'هذا الحجز لا ينتمي إلى الفندق المحدد.',
        'service_not_owned' => 'لا يمكنك ترك تقييم لحجز خدمة ليس لك.',
        'service_wrong_hotel' => 'هذا الحجز لا ينتمي إلى الفندق المحدد.',
    ],
];
