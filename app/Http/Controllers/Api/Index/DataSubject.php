<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index;

use App\Http\Transformers\Api\FaqTransformer;
use App\Models\Option;
use App\Models\SystemOptionValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use GrahamCampbell\Markdown\Facades\Markdown;

class DataSubject
{
    /**
     * @param Request $request
     * @param string $subject
     * @return array|JsonResponse
     */
    public function __invoke(Request $request, string $subject)
    {
        return $this->getBySubject($subject);
    }

    /**
     * @param string $subject
     * @return array
     */
    private function getBySubject(string $subject)
    {
        switch ($subject) {
            case Option::NAME_PRIVACY:
                return $this->returnPrivacy();
            case Option::NAME_TERMS:
                return $this->returnTerms();
            case Option::NAME_CONTACTS:
                return $this->returnContacts();
        }
        return responseCommon()->apiDataSuccess();
    }

    /**
     * @return array
     */
    public function returnPrivacy()
    {
        /** @var Option|null $oOption */
        $oOption = Option::where('name', Option::NAME_PRIVACY)->first();
        if (is_null($oOption)) {
            return responseCommon()->apiDataSuccess([]);
        }
        /** @var SystemOptionValue|null $oItem */
        $oItem = SystemOptionValue::where('option_id', $oOption->id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiDataSuccess([]);
        }
        return responseCommon()->apiDataSuccess([
            'value' => $this->markdownToHtml($oItem->value),
        ]);
    }

    /**
     * @return array
     */
    public function returnTerms()
    {
        /** @var Option|null $oOption */
        $oOption = Option::where('name', Option::NAME_TERMS)->first();
        if (is_null($oOption)) {
            return responseCommon()->apiDataSuccess([]);
        }
        /** @var SystemOptionValue|null $oItem */
        $oItem = SystemOptionValue::where('option_id', $oOption->id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiDataSuccess([]);
        }
        return responseCommon()->apiDataSuccess([
            'value' => $this->markdownToHtml($oItem->value),
        ]);
    }

    /**
     * @return array
     */
    public function returnContacts()
    {
        /** @var Option|null $oOption */
        $oOption = Option::where('name', Option::NAME_CONTACTS)->first();
        if (is_null($oOption)) {
            return responseCommon()->apiDataSuccess([]);
        }
        /** @var SystemOptionValue|null $oItem */
        $oItem = SystemOptionValue::where('option_id', $oOption->id)->first();
        if (is_null($oItem)) {
            return responseCommon()->apiDataSuccess([]);
        }
        return responseCommon()->apiDataSuccess([
            'value' => $this->markdownToHtml($oItem->value),
        ]);
    }

    /**
     * @param string $value
     * @return string
     */
    private function markdownToHtml(string $value)
    {
        $html = Markdown::convertToHtml($value);
        return str_replace("\n", '', $html);
    }
}
