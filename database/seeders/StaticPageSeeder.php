<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'terms-and-conditions',
                'title' => [
                    'en' => 'Terms and Conditions',
                    'ar' => 'الشروط والأحكام',
                ],
                'content' => [
                    'en' => 'These are the terms and conditions of our application.',
                    'ar' => 'هذه هي الشروط والأحكام لتطبيقنا.',
                ],
            ],
            [
                'slug' => 'privacy-policy',
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
                ],
                'content' => [
                    'en' => 'This is our privacy policy and how we handle your data.',
                    'ar' => 'هذه هي سياسة الخصوصية الخاصة بنا وكيفية تعاملنا مع بياناتك.',
                ],
            ],
            [
                'slug' => 'about',
                'title' => [
                    'en' => 'About',
                    'ar' => 'من نحن',
                ],
                'content' => [
                    'en' => 'About our company and what we do.',
                    'ar' => 'معلومات عن شركتنا وما نقوم به.',
                ],
            ],
        ];

        foreach ($pages as $page) {
            StaticPage::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}
