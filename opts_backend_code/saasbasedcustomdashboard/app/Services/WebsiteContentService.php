<?php

namespace App\Services;

use App\Models\WebsiteContent;

class WebsiteContentService
{
    public function __construct(
        private WebsiteContent $websiteContent
    ) {
    }

    public function findAll()
    {
        return $this->websiteContent->get();
    }

    public function updateByPageName($pageName, $content)
    {
        return $this->websiteContent->where('page_name', $pageName)->update(['content' => $content]);
    }

    /**
     * update the particular faq page
     */
    public function updateFaq($id, $question, $content)
    {
        return $this->websiteContent->where('id', $id)->update([
            'content' => $content,
            'question' => $question
        ]);
    }

    public function getContentByPageName($pageName)
    {
        return $this->websiteContent->where('page_name', $pageName)->get();
    }
}
