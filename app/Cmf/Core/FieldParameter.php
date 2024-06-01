<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use App\Cmf\Project\User\UserController;
use Illuminate\Support\Str;

class FieldParameter
{
    const TYPE = 'dataType';
    const TITLE = 'title';
    const TITLE_TABLE = 'table_title';
    const TITLE_FORM = 'title_form';
    const TABLE_TITLE = 'table_title';
    const RELATIONSHIP = 'relationship';
    const RELATIONSHIP_SKIP = 'relationship_skip';
    const VALUES = 'values';
    const ORDER = 'order';
    const ORDER_METHOD = 'method';
    const ORDER_BY = 'by';
    const ALIAS = 'alias';
    const REQUIRED = 'required';
    const IN_TABLE = 'in_table';
    const ROLES = 'roles';
    const FORMAT = 'format';
    const EMPTY = 'empty';
    const SEARCH = 'search';
    const DELETE_TITLE = 'delete_title';
    const DELETE_VALUE = 'delete_value';
    const DEFAULT = 'default';
    const DATETIME = 'datetime';
    const GROUP_NAME = 'group';
    const GROUP_TITLE = 'group-title';
    const GROUP_COL = 'group-col';
    const GROUP_HIDE = 'group-hide';
    const MODAL_ONLY = 'modal_only';
    const MODAL_SHOW_ONLY = 'modal_show_only';
    const TABLE_ONLY = 'table_only';
    const LENGTH = 'length';
    const TOOLTIP = 'tooltip';
    const PLACEHOLDER = 'placeholder';
    const MULTIPLE = 'multiple';
    const MASK_PHONE = 'mask_phone';
    const RADIO_VALUES = 'radio_values';
    const HIDDEN = 'hidden';
    const DISABLED = 'disabled';
    const READONLY = 'readOnly';
    const SHOW_ONLY = 'show_only';
    const SELECTED_VALUES = 'selected_values';
    const SPLIT = 'split';
    const WHERE_IN = 'whereIn';
    const WHERE_IN_COLUMN = 'column';
    const WHERE_IN_VALUE = 'value';
    const COLORED = 'colored';
    const LIMIT = 'limit';
    const MODES = 'modes';


    const MASK_INSTAGRAM = 'mask_instagram';

    /**
     * @param string $model
     * @param string $field
     * @return mixed|null
     */
    public function getField(string $model, string $field)
    {
        $sPath = Str::ucfirst($model);
        $sClass = $sPath . Str::studly('_controller');
        $sClass = 'App\Cmf\Project\\' . $sPath . '\\' . $sClass;
        if (class_exists($sClass)) {
            /** @var UserController $oClass */
            $oClass = new $sClass();
            $model = $oClass::NAME;
            $oClass->prepareFieldsValues();
            return $oClass->fields[$field];
        }
        return null;
    }
}
