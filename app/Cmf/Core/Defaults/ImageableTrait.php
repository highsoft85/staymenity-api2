<?php

declare(strict_types=1);

namespace App\Cmf\Core\Defaults;

use App\Events\ChangeCacheEvent;
use App\Models\Image;
use App\Services\Image\ImageType;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ImageableTrait
{
    /**
     * Upload images in modal
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function imageUpload(Request $request, int $id)
    {
        $oItem = $this->findByClass($this->class, $id);
        $aImages = $this->getImagesByRequest($request);
        if (empty($aImages)) {
            return responseCommon()->jsonError([
                'error' => ['Изображений не найдено'],
            ]);
        }
        $validation = $this->imageValidation($request, $aImages);
        if (!is_null($validation)) {
            return responseCommon()->validationMessages($validation);
        }
        if (!$request->has('type')) {
            return responseCommon()->jsonError([
                'error' => ['Нет типа у загружаемого изображения'],
            ]);
        }
        $type = $request->get('type');
        $oService = (new ImageService());
        if ($type === ImageType::MODEL) {
            $oService->upload($oItem, $aImages, $this->image[ImageType::MODEL], ImageType::MODEL);
        }

        $returnData = [];
        if ($this->view === 'user' && Auth::user()->id === $oItem->id) {
            event(new ChangeCacheEvent('members:user_' . $oItem->id));
            $returnData['src'] = $oItem->image_square;
        }
        $returnData['view'] = $this->imageGetView($oItem, $type);
        $returnData['type'] = $type;
        $this->imageableAfterChangeImage($this->image, $oItem);
        return responseCommon()->success($returnData);
    }

    /**
     * Destroy single image
     *
     * @param Request $request
     * @param int $id
     * @param int $image_id
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function imageDestroy(Request $request, int $id, int $image_id)
    {
        $oItem = $this->findByClass($this->class, $id);
        $oImage = Image::find($image_id);
        if (is_null($oImage)) {
            return responseCommon()->validationMessages(null);
        }
        $type = $oImage->type;
        $oService = (new ImageService());
        if ($type === ImageType::MODEL) {
            $oService->delete($oItem, $oImage, $this->image[ImageType::MODEL], ImageType::MODEL);
        }

        $returnData = [];
        if (!Auth::guest() && $this->view === 'user' && Auth::user()->id === $oItem->id) {
            event(new ChangeCacheEvent('members:user_' . $oItem->id));
            $returnData['src'] = $oItem->image_square;
        }
        $returnData['view'] = $this->imageGetView($oItem, $type);
        $returnData['type'] = $type;
        //$this->imageableAfterChangeImage($this->image, $oItem);
        return responseCommon()->success($returnData);
    }

    /**
     * Set main single image
     *
     * @param Request $request
     * @param int $id
     * @param int $image_id
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function imageMain(Request $request, int $id, int $image_id)
    {
        $oItem = $this->findByClass($this->class, $id);
        /** @var Image|null $oImage */
        $oImage = Image::find($image_id);
        if (is_null($oImage)) {
            return responseCommon()->validationMessages(null);
        }
        $type = $oImage->type;
        $oService = (new ImageService());
        if ($type === ImageType::MODEL) {
            $oService->main($oItem, $oImage, $this->image[ImageType::MODEL], ImageType::MODEL);
        }
        $oImage->refresh();

        $returnData = [];
        if ($this->view === 'user' && Auth::user()->id === $oItem->id) {
            event(new ChangeCacheEvent('members:user_' . $oItem->id));
            $returnData['src'] = $oItem->image_square;
        }
        $returnData['view'] = $this->imageGetView($oItem, $type);
        $returnData['type'] = $type;
        //$this->imageableAfterChangeImage($this->image, $oItem);
        return responseCommon()->success($returnData);
    }

    /**
     * @param array $imageSettings
     * @param null|object $oItem
     */
    private function imageableAfterChangeImage(array $imageSettings, $oItem = null): void
    {
        if (isset($imageSettings['clear_cache']) && !is_null($oItem)) {
            $this->afterChange($this->cache, $oItem);
        }
    }

    /**
     * @param object $oItem
     * @param string $type
     * @return string
     * @throws \Throwable
     */
    private function imageGetView(object $oItem, string $type)
    {
        $images = [];
        if ($type === ImageType::MODEL) {
            $images = $oItem->modelImages;
        }
        return view('cmf.components.gallery.block', [
            'oItem' => $oItem,
            'col' => 3,
            'model' => $this->view,
            'images' => $images,
            'type' => $type,
        ])->render();
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|mixed|null
     */
    private function getImagesByRequest(Request $request)
    {
        $file = $request->hasFile('images') ? $request->file('images') : $request->get('images');
        $aImages = !is_null($file) ? $file : $request->get('path-images');
        return $aImages;
    }

    /**
     * @param Request $request
     * @param array $aImages
     * @return \Illuminate\Contracts\Validation\Validator|null
     */
    private function imageValidation(Request $request, array $aImages = [])
    {
        $aRules = $this->setRulesForUpload($aImages, 'upload');
        $rules = $aRules['rules'];
        $attributes = $aRules['attributes'];
        $validation = $this->validation($request, $rules, $attributes, 'upload');
        $checkValidate = $request->get('validate');
        if (($validation->fails() && !$request->exists('path-images') && is_null($checkValidate)) || ($checkValidate)) {
            return $validation;
        }
        return null;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws \Throwable
     */
    public function imageSupportModal(Request $request, int $id)
    {
        $oImage = Image::find($id);
        $tab = $request->get('tab');
        $model = $request->get('model');
        return responseCommon()->success([
            'view' => view('cmf.content.default.modals.tabs.images.dialogs.support', [
                'oImage' => $oImage,
                'tab' => $tab,
                'model' => $model,
            ])->render(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function imageSupportModalSave(Request $request, int $id)
    {
        $oImage = Image::find($id);
        $title = $request->get('title');
        $description = $request->get('description');
        $info = [];
        if (!empty($title)) {
            $info['title'] = $title;
        }
        if (!empty($description)) {
            $info['description'] = $description;
        }
        $source = $request->get('source');
        $oImage->update([
            'info' => $info,
            'source' => $source,
        ]);
        return responseCommon()->success([], __('cmf.toastr.success'));
    }
}
