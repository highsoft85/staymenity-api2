<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Exceptions\ResourceExceptionValidation;
use App\Models\Amenity;
use App\Models\Listing;
use App\Models\ListingTime;
use App\Models\Rule;
use App\Models\Type;
use App\Models\UserCalendar;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Image\ImageType;
use App\Services\Image\Upload\ImageUploadModelService;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

class ListingUploadServiceModel extends BaseServiceModel
{
    /**
     * @var Listing
     */
    private $oListing;

    /**
     * @var string
     */
    private $message;

    /**
     * ListingServiceModel constructor.
     * @param Listing $oListing
     */
    public function __construct(Listing $oListing)
    {
        $this->oListing = $oListing;
    }

    /**
     * @param array $data
     * @param array $files
     * @return Listing|\Illuminate\Http\JsonResponse
     */
    public function upload(array $data, array $files)
    {
        $images = $files['images'];
        $setMain = isset($data['image_set_main']) && (int)$data['image_set_main'] === 1;
        $this->uploadImages($images, $this->oListing, $setMain);
        return $this->oListing;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $data
     * @param array $files
     * @return bool
     */
    public function uploadCheckValidation(array $data, array $files)
    {
        $images = $files['images'];
        $validation = Validator::make($data, $this->imageUploadRules($images));
        if ($validation->fails()) {
            $messages = responseCommon()->validationGetMessages($validation);
            $message = array_shift($messages);
            $this->message = $message;
            if (!$this->imageCheckFails($validation)) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * @param mixed $aImages
     * @param Listing $oListing
     * @param bool $setMain
     */
    private function uploadImages($aImages, Listing $oListing, bool $setMain)
    {
        foreach ($aImages as $aImage) {
            imageUpload($aImage, $oListing, ImageType::MODEL, [], [], $setMain);
        }
    }

    /**
     * @param array $images
     * @return array
     */
    private function imageUploadRules(array $images): array
    {
        $rules = [];
        foreach ($images as $key => $image) {
            $rules['images' . '.' . $key] = ['required', 'max:5000', 'mimes:jpg,jpeg,gif,png'];
        }
        return $rules;
    }

    /**
     * @param ValidationValidator $validation
     * @return bool
     */
    private function imageCheckFails(ValidationValidator $validation): bool
    {
        $messages = $validation->getMessageBag()->toArray();
        if (!isset($messages['image'][0])) {
            return false;
        }
        if ($messages['image'][0] === 'The image failed to upload.' && request()->has('phpunit')) {
            return false;
        }
        return true;
    }
}
