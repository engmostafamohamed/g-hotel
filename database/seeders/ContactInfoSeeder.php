<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactInfo;

class ContactInfoSeeder extends Seeder
{
    public function run(): void
    {
        $contactInfos = [
            // Phone Numbers
            [
                'type' => 'phone',
                'value' => '+1-800-555-1234',
                'label' => ['en' => 'Main Line', 'ar' => 'الخط الرئيسي'],
                'hotel_location_id' => null
            ],
            [
                'type' => 'phone',
                'value' => '+1-800-555-5678',
                'label' => ['en' => 'Customer Service', 'ar' => 'خدمة العملاء'],
                'hotel_location_id' => null
            ],
            [
                'type' => 'phone',
                'value' => '+20-100-123-4567',
                'label' => ['en' => 'Reservations', 'ar' => 'الحجوزات'],
                'hotel_location_id' => 1
            ],
            [
                'type' => 'phone',
                'value' => '000',
                'label' => ['en' => 'Reception ', 'ar' => 'الاستقبال'],
                'hotel_location_id' => 2
            ],
            [
                'type' => 'phone',
                'value' => '001',
                'label' => ['en' => 'Room Service ', 'ar' => 'خدمة الغرف'],
                'hotel_location_id' => 1
            ],
            [
                'type' => 'phone',
                'value' => '002',
                'label' => ['en' => 'Kitchen ', 'ar' => 'المطبخ'],
                'hotel_location_id' => 1
            ],
            [
                'type' => 'phone',
                'value' => '003',
                'label' => ['en' => 'Restaurants ', 'ar' => 'المطاعم'],
                'hotel_location_id' => 1
            ],
            [
                'type' => 'phone',
                'value' => '004',
                'label' => ['en' => 'Other Services ', 'ar' => 'الخدمات الاخري'],
                'hotel_location_id' => 1
            ],
            [
                'type' => 'phone',
                'value' => '111',
                'label' => ['en' => 'Emergency  ', 'ar' => 'الطوارئ'],
                'hotel_location_id' => 1
            ],
            [
                'type' => 'twitter',
                'value' => 'https://x.com/luxhotel',
                'label' => ['en' => 'X (Twitter)', 'ar' => 'إكس (تويتر)'],
                'hotel_location_id' => null
            ],

            // Emails
            [
                'type' => 'email',
                'value' => 'info@example.com',
                'label' => ['en' => 'General Inquiries', 'ar' => 'الاستفسارات العامة'],
                'hotel_location_id' => null
            ],
            [
                'type' => 'email',
                'value' => 'reservations@luxhotel.com',
                'label' => ['en' => 'Hotel Reservations', 'ar' => 'حجوزات الفندق'],
                'hotel_location_id' => 2
            ],
            [
                'type' => 'email',
                'value' => 'support@travelhub.com',
                'label' => ['en' => 'Technical Support', 'ar' => 'الدعم الفني'],
                'hotel_location_id' => null
            ],

            // WhatsApp
            [
                'type' => 'whatsapp',
                'value' => '+20-111-222-3333',
                'label' => ['en' => 'WhatsApp Support', 'ar' => 'دعم واتساب'],
                'hotel_location_id' => 1
            ],

            // Facebook
            [
                'type' => 'facebook',
                'value' => 'https://facebook.com/luxhotel',
                'label' => ['en' => 'Facebook Page', 'ar' => 'صفحة فيسبوك'],
                'hotel_location_id' => 2
            ],
            [
                'type' => 'facebook',
                'value' => 'https://facebook.com/globalstays',
                'label' => ['en' => 'Global Stays', 'ar' => 'الإقامات العالمية'],
                'hotel_location_id' => null
            ],

            // Instagram
            [
                'type' => 'instagram',
                'value' => 'https://instagram.com/luxhotel',
                'label' => ['en' => 'Instagram Profile', 'ar' => 'حساب انستجرام'],
                'hotel_location_id' => 2
            ],
            [
                'type' => 'instagram',
                'value' => 'https://instagram.com/travelguide',
                'label' => ['en' => 'Travel Guide', 'ar' => 'دليل السفر'],
                'hotel_location_id' => null
            ],

            // LinkedIn
            [
                'type' => 'linkedin',
                'value' => 'https://linkedin.com/company/luxhotel',
                'label' => ['en' => 'LinkedIn Company Page', 'ar' => 'صفحة لينكدإن للشركة'],
                'hotel_location_id' => 2
            ],

            // Website
            [
                'type' => 'website',
                'value' => 'https://luxhotel.com',
                'label' => ['en' => 'Official Website', 'ar' => 'الموقع الرسمي'],
                'hotel_location_id' => 2
            ],
            [
                'type' => 'website',
                'value' => 'https://generaltravel.com',
                'label' => ['en' => 'Travel Portal', 'ar' => 'بوابة السفر'],
                'hotel_location_id' => null
            ],

            // Telegram
            [
                'type' => 'telegram',
                'value' => '@luxhotel_bot',
                'label' => ['en' => 'Telegram Bot', 'ar' => 'بوت تليجرام'],
                'hotel_location_id' => 2
            ],

            // TikTok
            [
                'type' => 'tiktok',
                'value' => 'https://tiktok.com/@luxhotel',
                'label' => ['en' => 'TikTok Channel', 'ar' => 'قناة تيك توك'],
                'hotel_location_id' => 2
            ],

            // Twitter (X)
            [
                'type' => 'twitter',
                'value' => 'https://x.com/luxhotel',
                'label' => ['en' => 'X (Twitter)', 'ar' => 'إكس (تويتر)'],
                'hotel_location_id' => null
            ],
        ];

        foreach ($contactInfos as $info) {
            ContactInfo::create($info);
        }
    }
}
