<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use App\Cmf\Core\Parameters\TableParameter;
use App\Cmf\Core\Parameters\TabParameter;
use App\Cmf\Project\Role\RoleController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

abstract class MainController extends Controller
{
    use SettingsTrait;
    use ControllerCrmTrait;

    /**
     * Имя сущности
     */
    const NAME = 'default';
    const TITLE = 'Default';

    const RELATIONSHIP_BELONGS_TO_MANY = 1;
    const RELATIONSHIP_BELONGS_TO = 2;
    const RELATIONSHIP_HAS_ONE = 3;
    const RELATIONSHIP_HAS_MANY = 4;

    const DATA_TYPE_TEXT = 1;
    const DATA_TYPE_SELECT = 2;
    const DATA_TYPE_CHECKBOX = 3;
    const DATA_TYPE_DATE = 4;
    const DATA_TYPE_TEXTAREA = 5;
    const DATA_TYPE_NUMBER = 6;
    const DATA_TYPE_FILE = 7;
    const DATA_TYPE_CUSTOM = 8;
    const DATA_TYPE_IMG = 9;
    const DATA_TYPE_JSON = 10;
    const DATA_TYPE_RADIO = 11;
    const DATA_TYPE_COLOR = 12;
    const DATA_TYPE_MARKDOWN = 13;
    const DATA_TYPE_MARKDOWN_IMAGE = 14;

    const MODE_DEVELOPER = 'developer-mode';

    /**
     * @var string
     */
    protected $theme = 'cmf';

    /**
     * @var array
     */
    public $fields = [];

    /**
     * @var string
     */
    public $class = '';

    /**
     * @var string
     */
    public $session = '';

    /**
     * Реляции по умолчанию
     *
     * @var array
     */
    protected $with = [];

    /**
     * @var string
     */
    public $view = '';

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * Validation rules
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    public $cache = [];

    /**
     * @var array
     */
    public $tabs = [];

    /**
     * @var array
     */
    public $tabsDefault = [
        'edit' => [
            TabParameter::TAB_MAIN => TabParameter::TAB_MAIN_CONTENT,
        ],
        'show' => [
            'tabs.main' => [
                'title' => 'Данные',
                'tabs_attributes' => [
                    'aria-controls' => 'home',
                    'aria-expanded' => 'true',
                    'data-hidden-submit' => 0,
                ],
                'content_attributes' => [
                    'aria-expanded' => 'true',
                ],
            ],
        ],
    ];

    /**
     * @var array
     */
    public $image = [];


    /**
     * Отбор по умолчанию, например ['status' => 1, ]
     *
     * @var array
     */
    protected $aQuery = [];

    /**
     * @var null
     */
    protected $query = null;

    /**
     * Сортировка с учетом сессии, например ['column' => 'created_at', 'type' => 'desc']
     *
     * @var array
     */
    protected $aOrderBy = [
        'column' => 'created_at',
        'type' => 'desc',
    ];

    /**
     * Пути для шаблонов
     *
     * @see \App\Cmf\Core\ControllerCrmTrait::setViews()
     *
     * @var array
     */
    protected $views = [];

    /**
     * @var array
     */
    public $indexComponent = [];

    /**
     * Лимит для пагинации
     *
     * @var int
     */
    protected $tableLimit = 10;

    /**
     * UserBaseController constructor.
     * - распрарсить дефолтный квери
     * - распрарсить дефолтный ордер
     * - взять пути для шаблонов с кэша
     */

    public function __construct()
    {
        $this->updateFields();
        $this->setDefaults();

        /**
         * Пути для шаблонов
         */
        $this->views = $this->setViews();

        View::share('theme', $this->theme);
    }

    /**
     *
     */
    private function setDefaults()
    {
        $this->session = $this::NAME;
        $this->view = $this::NAME;

        /**
         * Если свойства не переопределены, то указываем дефолтное значение значение
         */
        if (empty($this->cache)) {
            $this->cache = [
                $this::NAME,
            ];
        }
//        if (empty($this->rules)) {
//            $this->rules = [
//                'store' => [],
//                'update' => [],
//            ];
//        }


        $this->indexComponent = array_merge([
            TableParameter::INDEX_SHOW => true,
            TableParameter::INDEX_TITLE_BORDERED => false,
            TableParameter::INDEX_SEARCH => true,
            TableParameter::INDEX_CREATE => true,
            TableParameter::INDEX_DELETE => true,
            TableParameter::INDEX_EDIT => true,
            TableParameter::INDEX_HISTORY => true,
            TableParameter::INDEX_IMAGE => true,
            TableParameter::INDEX_TITLE => $this::TITLE,
            TableParameter::INDEX_DESCRIPTION => null,
            TableParameter::INDEX_RELEASE_AT => false,
            TableParameter::INDEX_STATE => false,
            TableParameter::INDEX_MODAL_FAST_EDIT => false,
            TableParameter::INDEX_SOFT_DELETE => false,
            TableParameter::INDEX_EXPORT => false,
            TableParameter::INDEX_BREADCRUMBS => null,
        ], $this->indexComponent);

        if (empty($this->image)) {
            $this->image = [];
        }
        if (!empty($this->tabs) && !empty($this->tabs['edit'])) {
            $keys = array_keys($this->tabsDefault['edit']);
            foreach ($keys as $key) {
                if (isset($this->tabs['edit'][$key])) {
                    $this->tabs['edit'][$key] = array_merge($this->tabsDefault['edit'][$key], $this->tabs['edit'][$key]);
                }
            }
        }
        if (!empty($this->tabs) && !empty($this->tabs['show'])) {
            $keys = array_keys($this->tabs['show']);
            foreach ($keys as $key) {
                if (isset($this->tabs['show'][$key])) {
                    if (isset($this->tabs['edit'][$key])) {
                        $this->tabs['show'][$key] = array_merge($this->tabs['edit'][$key], $this->tabs['show'][$key]);
                    }
                    if (isset($this->tabsDefault['show'][$key])) {
                        $this->tabs['show'][$key] = array_merge($this->tabsDefault['show'][$key], $this->tabs['show'][$key]);
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): \Illuminate\View\View
    {
        if (method_exists($this, 'thisBeforePaginate')) {
            $this->thisBeforePaginate();
        }
        if (!empty($this->indexComponents)) {
            View::share('indexComponents', $this->indexComponents);
        }
        $oItems = $this->paginate($this->class);

        //Session::put($this->session . '.count.get', $oItems->total());
        //Session::put($this->session . '.count.total', $this->class::count());

        $this->prepareFieldsForTable();
        $this->prepareFieldsValues();
        $this->searchTableFields();
        $this->sortTableFields();

        if (method_exists($this, 'thisPrepareIndexComponent')) {
            $this->thisPrepareIndexComponent();
        }
        View::share('indexComponent', $this->indexComponent);

        if ($request->exists('id')) {
            $oItem = $this->findByClass($this->class, $request->get('id'));
            View::share('oItem', $oItem);
        }

        return view($this->views[__FUNCTION__], [
            'oItems' => $oItems,
        ]);
    }

    /**
     * Default query items
     *
     * @param string $oModel
     * @param string|null $type
     * @param array $aColumns
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($oModel, $type = null, $aColumns = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        if (!is_null($this->query)) {
            $oItems = $this->query;
        } else {
            $oItems = $oModel::with($this->with);

            if (method_exists($this, 'thisQuery')) {
                $oItems = $this->thisQuery($oItems);
            }

            if (isset($this->aOrderBy['translation'])) {
                $oItems = $oItems->orderByTranslation($this->aOrderBy['column'], $this->aOrderBy['type']);
            } else {
                $oItems = $oItems->orderBy($this->aOrderBy['column'], $this->aOrderBy['type']);
            }
            if (!is_null($type) && $type === 'search') {
                foreach ($aColumns as $key => $value) {
                    $oItems = $this->querySearchBy($oItems, $key, $value);
                }
            }
            if (!empty($this->aQuery)) {
                foreach ($this->aQuery as $key => $value) {
                    $oItems = $oItems->where($key, $value);
                }
            }
        }
        return $oItems->paginate($this->tableLimit)->withPath(routeCmf($this->view . '.view.post'));
    }

    /**
     * Поиск
     *
     * @param object $oItems
     * @param string $key
     * @param array|string|null $value
     * @return mixed
     */
    private function querySearchBy($oItems, $key, $value)
    {
        // Кастомный метод для фильтрации полей с нестандартными связями. Например для ролей пользователя
        $searchMethod = Str::camel($key) . 'FieldSearch';
        if (method_exists($this, $searchMethod)) {
            if (($value === '0' || (int)$value === 0) && !empty($this->fields[$key]['zero_good'])) {
                return $this->$searchMethod($oItems, $value);
            }
            if (empty($value)) {
                return $oItems;
            }
            return $this->$searchMethod($oItems, $value);
        }

        if (is_array($value)) {
            if (isset($value['translation']) && $value['translation'] === 'like') {
                if (property_exists(new $this->class(), 'translatedAttributes')) {
                    if (!is_null($value['value'])) {
                        $oItems = $oItems->where(function ($query) use ($value, $key) {
                            $query->whereTranslationLike($key, '%' . $value['value'] . '%');
                            if (isset($value['support'])) {
                                $query->orWhereTranslationLike($value['support'], '%' . $value['value'] . '%');
                            }
                        });
                    }
                } else {
                    if (isset($value['support'])) {
                        $oItems = $oItems->where(function ($query) use ($value, $key) {
                            $query->where($key, 'like', '%' . $value['value'] . '%')
                                ->orWhere($value['support'], 'like', '%' . $value['value'] . '%');
                        });
                    } else {
                        $oItems = $oItems->where($key, 'like', '%' . $value['value'] . '%');
                    }
                }
            }
            if (isset($value['type']) && $value['type'] === 'like') {
                $oItems = $oItems->where($key, 'like', '%' . $value['value'] . '%');
            }
            if (!empty($value['begin'])) {
                $oItems = $oItems->where($key, '>=', Carbon::parse($value['begin']));
            }
            if (!empty($value['end'])) {
                $oItems = $oItems->where($key, '<', Carbon::parse($value['end'])->addDay());
            }
            if (!empty($value['from'])) {
                $oItems = $oItems->where($key, '>=', $value['from']);
            }
            if (!empty($value['to'])) {
                $oItems = $oItems->where($key, '<', $value['to']);
            }
            if ($key === 'roles' && !empty($value)) {
                $value = array_filter($value);
                if (!empty($value)) {
                    $oItems = $oItems->whereHas($key, function ($q) use ($value) {
                        $q->whereIn('id', $value);
                    });
                }
            }
        } else {
            if (method_exists(new $this->class(), $key) && !empty($value)) {
                // Фильтрация по связанным полям
                $oItems = $oItems->whereHas($key, function ($q) use ($value) {
                    $q->where('id', $value);
                });
            } elseif (!is_null($value)) {
                $oItems = $oItems->where($key, 'like', '%' . $value . '%');
            }
        }
        return $oItems;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view($this->views[__FUNCTION__]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array | JsonResponse
     */
    public function store(Request $request)
    {
        $this->prepareRequestDataBeforeValidation($request);
        $validation = $this->validation($request, $this->getRules(), $this->getAttributes(), __FUNCTION__);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        } else {
            $returnData = [];
            $this->prepareRequestDataAfterValidation($request);
            if (method_exists($this, 'thisCreate')) {
                $return = $this->thisCreate($request);
                if ($return instanceof JsonResponse) {
                    return $return;
                }
                if (isset($return['success'])) {
                    unset($return['success']);
                }
                $returnData = $return;
            } else {
                $model = $this->storeModel($request);
                if ($request->exists('edit')) {
                    Session::put('last_create', $model->id);
                }
            }
            return responseCommon()->success($returnData, $this->toastText[__FUNCTION__]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storeModel(Request $request)
    {
        $model = $this->class::create($request->all());
        $this->saveRelationships($model, $request);
        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id): \Illuminate\View\View
    {
        $oItem = $this->findByClass($this->class, $id);

        $this->prepareFieldsValues($oItem);

        return view($this->views[__FUNCTION__], [
            'oItem' => $oItem,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, $id): \Illuminate\View\View
    {
        $request->merge([
            'id' => $id,
        ]);
        return $this->index($request);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array|JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $this->prepareRequestDataBeforeValidation($request);
        $validation = $this->validation($request, $this->getRules(), $this->getAttributes(), __FUNCTION__);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }
        $model = $this->findByClass($this->class, $id);
        $validation = $this->validation($request, $this->getRules($model, $request->all()), $this->getAttributes(), __FUNCTION__);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }
        $this->prepareRequestDataAfterValidation($request);
        $returnData = [];
        if (method_exists($this, 'thisUpdate')) {
            $return = $this->thisUpdate($request, $model);
            if (!$return) {
                $return = responseCommon()->success($returnData, $this->toastText[__FUNCTION__]);
            }
        } else {
            $model->update($request->all());
            $this->saveRelationships($model, $request);
            $return = responseCommon()->success($returnData, $this->toastText[__FUNCTION__]);
        }
        if (method_exists($this, 'thisAfterChange')) {
            $this->thisAfterChange($model);
        }
        return $return;
    }

    /**
     * Изменить статус пользователю
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function status(Request $request, int $id): array
    {
        $oItem = $this->findByClass($this->class, $id);
        $oItem->update([
            'status' => $request->get('status'),
        ]);
        return responseCommon()->success([], $this->toastText[__FUNCTION__]);
    }

    /**
     * @param int $id
     * @return array
     */
    public function destroy(int $id): array
    {
        $model = $this->findByClass($this->class, $id);
        if (method_exists($this, 'thisDestroy')) {
            $result = transaction()->commitAction(function () use ($model) {
                $this->thisDestroy($model);
            });
            if (!$result->isSuccess()) {
                return responseCommon()->error([], $result->getErrorMessage());
            }
        } else {
            $model->delete();
            //$this->afterChange($this->cache);
        }
        return responseCommon()->success([], $this->toastText[__FUNCTION__]);
    }

    /**
     * Custom action
     *
     * @param Request $request
     * @param string $name
     * @return mixed
     */
    public function action(Request $request, string $name)
    {
        $method = Str::camel($name);
        if (!method_exists($this, $method)) {
            abort(500, 'Method not found');
        }
        return $this->{$method}($request);
    }

    /**
     * Генерация после добавление/изменения/удаления
     *
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function view(Request $request): array
    {
        if ($request->exists('to')) {
            $method = $request->get('to') . 'Query';
            if (method_exists($this, $method)) {
                $this->query = $this->$method($request);
            }
            $request->request->remove('to');
        }
        return $this->query($request);
    }

    /**
     * Обновление таблицы после добавление/изменения/удаления
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $oItems
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    private function table($oItems): array
    {
        $this->prepareFieldsForTable();
        $this->prepareFieldsValues();
        $this->searchTableFields();
        $this->sortTableFields();

        View::share('indexComponent', $this->indexComponent);

        $view = view($this->views[__FUNCTION__], [
            'oItems' => $oItems,
        ])->render();

        $data = [
            'view' => $view,
            'count' => $oItems->total(),
        ];
        if (Session::exists('last_create')) {
            $data['id'] = Session::get('last_create');
            Session::forget('last_create');
        }
        return responseCommon()->success($data);
    }

    /**
     * @param Request $request
     * @param string $type
     * @param int|null $id
     * @return array
     * @throws \Throwable
     */
    public function modal(Request $request, string $type, ?int $id = null)
    {
        $oItem = $id ? $this->findByClass($this->class, $id) : null;
        if ($type === 'show') {
            $this->prepareFieldsForShow();
        }
        $this->prepareFieldsByModal($type, $oItem);
        $this->prepareFieldsValues($oItem);
        $tabs = $this->tabs[$type] ?? [];
        $tabs = $this->prepareTabsByRole($tabs, $oItem);
        $tabs = $this->prepareTabsByMode($tabs, $oItem);
        $tabsScrolling = false;
        if (isset($this->tabs['scrolling']) && isset($this->tabs['scrolling']['modes'])) {
            if ($this->checkModes($this->tabs['scrolling']['modes'])) {
                $tabsScrolling = true;
            }
        } else {
            $tabsScrolling = $this->tabs['scrolling'] ?? false;
        }
        if (isset($this->tabs['scrolling']) && isset($this->tabs['scrolling']['roles'])) {
            /** @var User $oUser */
            $oUser = Auth::user();
            $tabsScrolling = $oUser->hasAnyRole($this->tabs['scrolling']['roles']);
        }
        $data = [
            'oItem' => $oItem,
            'tabs' => $tabs,
            'tabsScrolling' => $tabsScrolling,
            'view' => $this->view,
        ];
        //if ($type === 'create' && $this->class !== Refer::class) {
        if ($type === 'create') {
            $data['fastEdit'] = $this->indexComponent[TableParameter::INDEX_MODAL_FAST_EDIT] ?? false;
            if ($this->indexComponent[TableParameter::INDEX_MODAL_FAST_EDIT]) {
                //$data['submitText'] = $this->indexComponent[TableParameter::INDEX_MODAL_FAST_EDIT];
            }
        }

        if ($type === 'edit' && method_exists($this, 'thisEditDataModal')) {
            $data = array_merge($data, $this->thisEditDataModal($oItem));
        }
        if ($type === 'create' && $request->exists('default')) {
            foreach ($request->get('default') as $key => $value) {
                if (isset($this->fields[$key]) && $this->fields[$key][FieldParameter::TYPE] === MainController::RELATIONSHIP_BELONGS_TO) {
                    $this->fields[$key]['selected_values'][] = $value;
                    $this->fields[$key]['show_only'] = true;
                    $this->shareToView('fields', $this->fields);
                }
            }
        }
        $view = view($this->views['modal'] . $type, $data)->render();

        return responseCommon()->success([
            'view' => $view,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function query(Request $request): array
    {
        $columns = [];
        foreach ($request->all() as $key => $value) {
            switch ($key) {
                default:
                    $columns[$key] = $value;
                    break;
            }
        }
        if (isset($columns['page'])) {
            unset($columns['page']);
        }
        if (isset($columns['multi_save'])) {
            unset($columns['multi_save']);
        }
        if (isset($columns['phpunit'])) {
            unset($columns['phpunit']);
        }
        $oItems = $this->paginate($this->class, 'search', $columns);
        return $this->table($oItems);
    }

    /**
     *
     */
    private function updateFields(): void
    {
        if (method_exists($this, 'buildFields')) {
            $this->fields = $this->buildFields();
        }
    }

    /**
     * @param string $modal
     * @param object|null $item
     */
    private function prepareFieldsByModal(string $modal, $item = null)
    {
        foreach ($this->fields as $key => &$value) {
            if (isset($value[FieldParameter::MODAL_ONLY])) {
                if (!in_array($modal, $value[FieldParameter::MODAL_ONLY])) {
                    unset($this->fields[$key]);
                }
            }
            if (isset($value[FieldParameter::TABLE_ONLY])) {
                unset($this->fields[$key]);
            }
            if (isset($value[FieldParameter::MODAL_SHOW_ONLY])) {
                if (in_array($modal, $value[FieldParameter::MODAL_SHOW_ONLY])) {
                    $this->fields[$key]['show_only'] = true;
                }
            }
        }
    }

    /**
     * @param object|null $item
     */
    public function prepareFieldsValues($item = null)
    {
        if (method_exists($this, 'thisPrepareFieldsValues')) {
            $this->thisPrepareFieldsValues($item);
        }
        foreach ($this->fields as $key => &$value) {
            $keyName = $key;
            if (isset($value['name_alias'])) {
                $keyName = $value['name_alias'];
            }
            if (isset($value[FieldParameter::VALUES])) {
                $value['selected_values'] = [];
                if (isset($item) && !is_null($item->$keyName)) {
                    if (is_string($item->$keyName)) {
                        $value['selected_values'][] = $item->$keyName;
                    } elseif ($item->$keyName instanceof \Illuminate\Database\Eloquent\Collection) {
                        $value['selected_values'] = $item->$keyName->pluck('id')->toArray();
                    } elseif (is_int($item->$keyName)) {
                        $value['selected_values'][] = $item->$keyName;
                    } else {
                        $value['selected_values'][] = $item->$keyName->id ?? 'None';
                    }
                }
                if (!is_array($value[FieldParameter::VALUES]) && (int)$value[FieldParameter::TYPE] !== self::DATA_TYPE_CUSTOM) {
                    $method = $value[FieldParameter::ORDER][FieldParameter::ORDER_METHOD] ?? 'orderBy';
                    $name = $value[FieldParameter::ORDER][FieldParameter::ORDER_BY] ?? 'name';
                    $dir = $value[FieldParameter::ORDER]['dir'] ?? 'asc';
                    $pluckName = $value[FieldParameter::ALIAS] ?? $name;
                    $class = $value[FieldParameter::VALUES];
                    $value[FieldParameter::VALUES] = $class::count() < 100
                        ? $class::$method($name, $dir)
                        : $class::whereIn((new $class())->getTable() . '.id', $value['selected_values'])->$method($name, $dir);

                    if (isset($value['whereIn'])) {
                        $value[FieldParameter::VALUES]->whereIn($value['whereIn']['column'], $value['whereIn']['value']);
                    }
                    if ($class === Role::class) {
                        $value[FieldParameter::VALUES]->whereNotIn('name', RoleController::reject());
                    }
                    $value[FieldParameter::VALUES] = $value[FieldParameter::VALUES]->get()->pluck($pluckName, 'id');
                }
            }
            if ($key === 'status' && $value[FieldParameter::TYPE] === self::DATA_TYPE_SELECT) {
                $value[FieldParameter::VALUES] = (new $this->class())->getStatuses();
                $value[FieldParameter::SELECTED_VALUES] = [];
                if (!is_null($item)) {
                    $value[FieldParameter::SELECTED_VALUES] = [
                        $item->status,
                    ];
                }
            }
            if ($value[FieldParameter::TYPE] === 'by_option' && !is_null($item)) {
                $value[FieldParameter::TYPE] = $item->option->type;
            }
        }

        $this->shareToView('fields', $this->fields);
        $this->shareToView('model', $this::NAME);
    }

    /**
     * @param array $tabs
     * @param object|null $item
     * @return array
     */
    private function prepareTabsByRole(array $tabs = [], $item = null)
    {
        /** @var User $oUser */
        $oUser = Auth::user();
        foreach ($tabs as $key => $tab) {
            if (isset($tab['roles']) && !$oUser->hasAnyRole($tab['roles'])) {
                unset($tabs[$key]);
            }
        }
        return $tabs;
    }

    /**
     * @param array $tabs
     * @param object|null $item
     * @return array
     */
    private function prepareTabsByMode(array $tabs = [], $item = null)
    {
        foreach ($tabs as $key => $tab) {
            if (isset($tab['modes']) && !$this->checkModes($tab['modes'])) {
                unset($tabs[$key]);
            }
        }
        return $tabs;
    }

    /**
     *
     */
    private function prepareFieldsForTable()
    {
        /** @var User $oUser */
        $oUser = Auth::user();
        foreach ($this->fields as $key => $value) {
            if (isset($value['roles']) && !$oUser->hasAnyRole($value['roles'])) {
                unset($this->fields[$key]);
            }
            if (isset($value['modes']) && !$this->checkModes($value['modes'])) {
                unset($this->fields[$key]);
            }
        }
    }

    /**
     *
     */
    private function prepareFieldsForShow()
    {
        /** @var User $oUser */
        $oUser = Auth::user();
        foreach ($this->fields as $key => $value) {
            if (isset($value['roles']) && !$oUser->hasAnyRole($value['roles'])) {
                unset($this->fields[$key]);
            }
        }
    }

    public function sortTableFields()
    {
        $this->fields = collect($this->fields)->reject(function ($field) {
            return !isset($field['in_table']);
        })->sortBy('in_table')->toArray();
    }

    /**
     * @param Request $request
     */
    public function prepareRequestDataBeforeValidation(Request &$request)
    {
        foreach ($this->fields as $name => $field) {
            if ($field['dataType'] === self::DATA_TYPE_CHECKBOX && !$request->has($name)) {
                $request->request->add([$name => false]);
            }
            if ($field['dataType'] === self::DATA_TYPE_DATE) {
                $aDates = (new $this->class())->getDates();
                foreach ($aDates as $date) {
                    if ($request->exists($date) && !empty($request->get($date))) {
                        $request->merge([
                            $date => $request->get($date) instanceof \DateTime ? $request->get($date) : Carbon::parse($request->get($date)),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @param Request $request
     */
    public function prepareRequestDataAfterValidation(Request &$request): void
    {
        foreach ($this->fields as $name => $field) {
            if ($field['dataType'] === self::DATA_TYPE_IMG) {
                if ($request->hasFile($name)) {
                    $file_path = $request->$name->store('public/' . $this->view . '_' . $name);

                    $new_request = new Request();
                    $new_request->replace($request->except($name));
                    $request = $new_request;

                    $request->merge([
                        $name => $file_path,
                    ]);
                } else {
                    $request->offsetUnset($name);
                }
            }
        }
    }

    /**
     * @param object $model
     * @param Request $request
     */
    public function saveRelationships($model, Request $request): void
    {
        foreach ($this->fields as $name => $field) {
            if (!isset($field['relationship'])) {
                continue;
            }
            if (isset($field['relationship_skip'])) {
                continue;
            }
            switch ($field['relationship']) {
                case self::RELATIONSHIP_BELONGS_TO_MANY:
                    $model->$name()->sync($request->get($name));
                    break;
                case self::RELATIONSHIP_BELONGS_TO:
                    $model->$name()->associate($field['values']::find($request->get($name)));
                    $model->save();
                    break;
            }
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws \Throwable
     */
    public function getRelationshipFieldModal(Request $request, $id)
    {
        $field = $request->get('field');
        $key = $request->get('key');
        $type = empty($this->fields[$field]['multiple']) ? 'show' : 'list';
        $oItem = $this->findByClass($this->class, $id);
        $model = self::getModelNameByClass($this->fields[$field]['values']);
        $Controller = self::getControllerByModelName($model);
        $Controller->prepareFieldsValues();

        $data = [
            'title' => $this->fields[$field]['title'],
            'model' => strtolower($model),
        ];

        if ($key) {
            $field = $oItem->$field()->whereId($key)->first();
            $type = 'show';
        } else {
            //$field = empty($method) ? $oItem->$field : $oItem->$method();
            $field = $oItem->$field;
        }

        $data[in_array(self::getModelNameByClass(get_parent_class($field)), ['Model', 'User']) ? 'oItem' : 'oItems'] = $field;

        /**
         * @see ControllerCrmTrait::setViews()
         */
        $view = view('cmf.content.default.modals.container.' . $type, $data)->render();

        return responseCommon()->success([
            'view' => $view,
        ]);
    }

    /**
     * Фильтры
     *
     * @return array
     */
    public function getImageFilters(): array
    {
        return $this->image['filters'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchRelationshipField(Request $request)
    {
        $field = $request->input('field');

        if (isset($this->fields[$field][FieldParameter::VALUES])) {
            $search = $request->input('q');
            $selected = !empty($request->input('selected')) ? $selected = explode(',', $request->input('selected')) : [];
            $model = self::getModelNameByClass($this->fields[$field][FieldParameter::VALUES]);
            $Controller = self::getControllerByModelName($model);

            $method = $this->fields[$field][FieldParameter::ORDER][FieldParameter::ORDER_METHOD] ?? 'orderBy';
            $name = $this->fields[$field][FieldParameter::ORDER][FieldParameter::ORDER_BY] ?? 'name';
            $dir = $this->fields[$field][FieldParameter::ORDER]['dir'] ?? 'asc';
            $class = $this->fields[$field][FieldParameter::VALUES];
            $pluckName = $this->fields[$field][FieldParameter::ALIAS] ?? $name;

            if (!is_array($this->fields[$field][FieldParameter::VALUES]) && $this->fields[$field][FieldParameter::TYPE] !== self::DATA_TYPE_CUSTOM) {
                $selected = $class::whereIn((new $class())->getTable() . '.id', $selected);
                if ($class === Role::class) {
                    $selected->whereNotIn('name', RoleController::reject());
                }
                $selected = $selected->$method($name, $dir)->get()->pluck($pluckName, 'id');
            }
            if (!is_array($this->fields[$field][FieldParameter::VALUES]) && $this->fields[$field][FieldParameter::TYPE] !== self::DATA_TYPE_CUSTOM) {
                $search = $class::where($name, 'like', $search . '%');
                if ($class === Role::class) {
                    $search->whereNotIn('name', RoleController::reject());
                }
                $search = $search->$method($name, $dir)->limit(20)->get()->pluck($pluckName, 'id');
            }
            return responseCommon()->success([
                'search' => $search,
                'selected' => $selected,
            ]);
        }
        return responseCommon()->error([]);
    }

    /**
     * @param object|null $model
     * @param array $data
     * @return array
     */
    public function getRules($model = null, $data = [])
    {
        if (method_exists($this, 'rules')) {
            return $this->rules($model, $data);
        }
        if (!empty($this->rules)) {
            return $this->rules;
        }
        return [
            'store' => [],
            'update' => [],
        ];
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes ?? [];
    }

    /**
     * @return string
     */
    protected function getCurrentView(): string
    {
        return $this->theme . '.content.' . $this->view;
    }

    /**
     *
     */
    public function searchTableFields()
    {
        $search_fields = collect($this->fields)->reject(function ($field) {
            return !isset($field['search']);
        })->sortBy('search')->toArray();
        $this->shareToView('search_fields', $search_fields);
    }

    /**
     * @param array $modes
     * @return bool
     */
    private function checkModes(array $modes)
    {
        $hasAccess = false;
        foreach ($modes as $mode) {
            switch ($mode) {
                case self::MODE_DEVELOPER:
                    $hasAccess = $this->checkDeveloperMode();
                    break;
            }
        }
        return $hasAccess;
    }

    /**
     * @return bool
     */
    private function checkDeveloperMode()
    {
        /** @var User $oUser */
        $oUser = Auth::user();
        if ($oUser->hasAnyRole([User::ROLE_DEVELOPER])) {
            return true;
        }
        if (Session::exists(self::MODE_DEVELOPER)) {
            return true;
        }
        return false;
    }
}
