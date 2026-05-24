<?php

namespace App\Http\Repository\V1\Api\StaticPages;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageRepository
{
    public function getAboutPage()
    {
        $page = StaticPage::where('slug','about')
            ->where('is_active',true)
            ->first();
        if (!$page) {
            return ['status' => 'not_found'];
        }
        return [
            'status' => 'success',
            'aboutData' => $page,
        ];
    }
    public function getTermsAndConditionsPage()
    {
        $page = StaticPage::where('slug','terms-and-conditions')
        ->where('is_active',true)
        ->first();
        if (!$page) {
            return ['status' => 'not_found'];
        }
        return [
            'status' => 'success',
            'termsAndConditionData' => $page,
        ];
    }
    public function getPrivacyAndPolicyPage()
    {
        $page = StaticPage::where('slug','privacy-policy')
            ->where('is_active',true)
            ->first();
        if (!$page) {
            return ['status' => 'not_found'];
        }
        return [
            'status' => 'success',
            'privacyAndPolicyData' => $page,
        ];

    }
    public function getContactPage(){
        $page = StaticPage::where('slug','contact')
            ->where('is_active',true)
            ->first();
        if (!$page) {
            return ['status' => 'not_found'];
        }
        return [
            'status' => 'success',
            'privacyAndPolicyPageData' => $page,
        ];
    }
}
