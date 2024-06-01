<?php

declare(strict_types=1);

namespace App\Cmf\Core\Parameters;

use App\Models\User;

class TableParameter
{
    const INDEX_SHOW = 'show';
    const INDEX_SEARCH = 'search';
    const INDEX_TITLE_BORDERED = 'title_bordered';
    const INDEX_CREATE = 'create';
    const INDEX_DELETE = 'delete';
    const INDEX_DELETE_DISABLED = 'delete_disabled';
    const INDEX_EDIT = 'edit';
    const INDEX_EDIT_DISABLED = 'edit_disabled';
    const INDEX_PRIVATE_SHOW = 'private_show';
    const INDEX_HISTORY = 'history';
    const INDEX_IMAGE = 'image';
    const INDEX_DESCRIPTION = 'description';
    const INDEX_TITLE = 'title';
    const INDEX_RELEASE_AT = 'release_at';
    const INDEX_STATE = 'state';
    const INDEX_WITH_TRASHED = 'with_trashed';


    const INDEX_MODAL_FAST_EDIT = 'modal_fast_edit';
    const INDEX_SOFT_DELETE = 'soft_delete';
    const INDEX_EXPORT = 'export';
    const INDEX_BREADCRUMBS = 'breadcrumbs';
    const INDEX_SEARCH_FIELDS = 'search_fields';
}
