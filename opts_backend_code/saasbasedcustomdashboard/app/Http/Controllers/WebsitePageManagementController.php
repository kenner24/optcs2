<?php

namespace App\Http\Controllers;

use App\Enums\WebsitePageTypeEnum;
use App\Http\Controllers\Controller;
use App\Services\WebsiteContentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class WebsitePageManagementController extends Controller
{
    public function __construct(
        private WebsiteContentService $websiteContentService
    ) {
    }


    public function view()
    {
        $data = $this->websiteContentService->findAll();
        return view('superAdmin.websitePages.index', ['data' => $data]);
    }


    public function updateContent(Request $request)
    {
        $payload = $request->all();
        foreach ($payload as $pageName => $pageValue) {
            if (Str::contains($pageName, 'faq_question')) {
                $splitString = explode("_", $pageName);
                $id = $splitString[2];
                $answer = Arr::first($payload, function ($value, $key) use ($id) {
                    return Str::contains($key, "faq_answer_" . $id);
                });
                $this->websiteContentService->updateFaq($id, $pageValue, $answer);
            } else {
                $this->websiteContentService->updateByPageName($pageName, $pageValue);
            }
        }

        return back()->with('success', 'Content updated successfully.');
    }

    public function getPageContent(Request $request)
    {
        $payload = $request->validate([
            'page_type' => [
                'required',
                Rule::in([
                    WebsitePageTypeEnum::ABOUT_US->value,
                    WebsitePageTypeEnum::PRIVACY_POLICY->value,
                    WebsitePageTypeEnum::TERMS_OF_USE->value,
                    WebsitePageTypeEnum::FAQ->value,
                ])
            ]
        ]);

        $result = $this->websiteContentService->getContentByPageName($payload['page_type']);
        return $this->sendResponse('Success', ['content' => $result]);
    }
}
