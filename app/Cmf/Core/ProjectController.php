<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Экземпляр контроллера сущности
     *
     * @var null
     */
    protected $oController = null;

    /**
     * Класс сущности/сам контролер,
     *
     * @var string|null
     */
    private $sClass;

    /**
     * ProjectController constructor.
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     * Autoload controller file
     */
    private function load()
    {
        $prefix = config('cmf.as');
        $current = Route::current();
        $as = $current->action['as'] ?? '';

        if ($prefix !== '') {
            $as = str_replace($prefix . '.', '', $as);
        }
        $sPath = Str::studly(stristr($as, '.', true));
        $this->sClass = $sPath . Str::studly('_controller');
        $this->sClass = 'App\Cmf\Project\\' . $sPath . '\\' . $this->sClass;
    }

    /**
     * Контроллер
     *
     * @return mixed
     */
    private function controller()
    {
        return new $this->sClass();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->controller()->index($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->controller()->create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->controller()->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->controller()->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        return $this->controller()->edit($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        return $this->controller()->update($request, $id);
    }

    /**
     * Update the status in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, int $id)
    {
        return $this->controller()->status($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->controller()->destroy($id);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return mixed|void
     */
    public function action(Request $request, string $name)
    {
        return method_exists($this->controller(), $name)
            ? $this->controller()->{$name}($request)
            : abort(404, 'Method ' . $name . ' not found in ' . get_class($this->controller()));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string $name
     * @return mixed
     */
    public function actionItem(Request $request, int $id, string $name)
    {
        return $this->controller()->{$name}($request, $id);
    }

    /**
     * Update view after add/edit/delete
     *
     * @param Request $request
     * @return mixed
     */
    public function view(Request $request)
    {
        return $this->controller()->view($request);
    }

    /**
     * Create modal window for add item
     *
     * @param Request $request
     * @return mixed
     */
    public function createModal(Request $request)
    {
        return $this->controller()->modal($request, 'create');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function showModal(Request $request, int $id)
    {
        return $this->controller()->modal($request, 'show', $id);
    }

    /**
     * Create modal window for edit item
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function editModal(Request $request, int $id)
    {
        return $this->controller()->modal($request, 'edit', $id);
    }

    /**
     * XHR upload images
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function imageUpload(Request $request, int $id)
    {
        return $this->controller()->imageUpload($request, $id);
    }

    /**
     * XHR upload images
     *
     * @param Request $request
     * @param int $id
     * @param int $image_id
     * @return mixed
     */
    public function imageDestroy(Request $request, int $id, int $image_id)
    {
        return $this->controller()->imageDestroy($request, $id, $image_id);
    }


    /**
     * XHR upload images
     *
     * @param Request $request
     * @param int $id
     * @param int $image_id
     * @return mixed
     */
    public function imageMain(Request $request, int $id, int $image_id)
    {
        return $this->controller()->imageMain($request, $id, $image_id);
    }

    /**
     * Sort items
     *
     * @param Request $request
     * @return mixed
     */
    public function query(Request $request)
    {
        return $this->controller()->query($request);
    }

    /**
     * Гет страница
     *
     * @param Request $request
     * @param string $name
     * @return mixed|void
     */
    public function getTo(Request $request, string $name)
    {
        return method_exists($this->controller(), $name)
            ? $this->controller()->{$name}($request)
            : abort(404, 'Method ' . $name . ' not found in ' . get_class($this->controller()));
    }

    /**
     * Гет страница
     *
     * @param Request $request
     * @param string $name
     * @param string $action
     * @return mixed|void
     */
    public function getToAction(Request $request, string $name, string $action)
    {
        $method = $name . Str::ucfirst($action);
        return method_exists($this->controller(), $method)
            ? $this->controller()->{$method}($request)
            : abort(404, 'Method ' . $method . ' not found in ' . get_class($this->controller()));
    }

    /**
     * Save modal with wysiwyg text
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function textSave(Request $request, int $id)
    {
        return $this->controller()->textSave($request, $id);
    }

    /**
     * Upload image with wysiwyg
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function textUpload(Request $request, int $id)
    {
        return $this->controller()->textUpload($request, $id);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string $name
     * @return mixed|void
     */
    public function getToItem(Request $request, int $id, string $name)
    {
        return method_exists($this->controller(), $name)
            ? $this->controller()->{$name}($request, $id)
            : abort(404, 'Method ' . $name . ' not found in ' . get_class($this->controller()));
    }
}
