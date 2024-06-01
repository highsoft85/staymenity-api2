<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use Illuminate\Support\Facades\Route;

abstract class RouteCmf
{
    public static function resource($name, array $options = [])
    {
        $class = '\\' . ProjectController::class;

        Route::get('/' . $name, ['uses' => $class . '@index', 'as' => $name . '.index']);
        Route::get('/' . $name . '/create', ['uses' => $class . '@create', 'as' => $name . '.create']);
        Route::post('/' . $name . '/create', ['uses' => $class . '@createModal', 'as' => $name . '.create.modal.post']);
        Route::post('/' . $name, ['uses' => $class . '@store', 'as' => $name . '.store']);
        Route::get('/' . $name . '/{id}', ['uses' => $class . '@show', 'as' => $name . '.show']);
        Route::get('/' . $name . '/{id}/edit', ['uses' => $class . '@edit', 'as' => $name . '.edit']);
        Route::post('/' . $name . '/{id}/edit', ['uses' => $class . '@editModal', 'as' => $name . '.edit.modal.post']);
        Route::post('/' . $name . '/{id}/show', ['uses' => $class . '@showModal', 'as' => $name . '.show.modal.post']);
        Route::post('/' . $name . '/{id}/update', ['uses' => $class . '@update', 'as' => $name . '.update']);
        Route::post('/' . $name . '/{id}/delete', ['uses' => $class . '@destroy', 'as' => $name . '.destroy']);

        //Route::post('/'.$name.'/{id}/image',                    ['uses' => $class.'@image',        'as' => $name.'.image.post']);
        //Route::post('/'.$name.'/{id}/files',                    ['uses' => $class.'@files',        'as' => $name.'.files.post']);
        Route::post('/' . $name . '/view', ['uses' => $class . '@view', 'as' => $name . '.view.post']);
        //Route::post('/'.$name.'/search',                        ['uses' => $class.'@search',       'as' => $name.'.search.post']);
        Route::post('/' . $name . '/action/{name}', ['uses' => $class . '@action', 'as' => $name . '.action.post']);
        Route::post('/' . $name . '/{id}/action/{name}', ['uses' => $class . '@actionItem', 'as' => $name . '.action.item.post']);
        Route::get('/' . $name . '/to/{name}', ['uses' => $class . '@getTo', 'as' => $name . '.action']);
        Route::get('/' . $name . '/to/{to}', ['uses' => $class . '@getTo', 'as' => $name . '.to']);
        Route::get('/' . $name . '/to/{name}/{action}', ['uses' => $class . '@getTo', 'as' => $name . '.action.action']);
        Route::get('/' . $name . '/{id}/{name?}', ['uses' => $class . '@getToItem', 'as' => $name . '.action.item']);

        Route::post('/' . $name . '/{id}/status', ['uses' => $class . '@status', 'as' => $name . '.status']);
        //Route::post('/'.$name.'/sort',                          ['uses' => $class.'@sort',         'as' => $name.'.sort']);
        Route::post('/' . $name . '/query', ['uses' => $class . '@query', 'as' => $name . '.query']);

        Route::post('/' . $name . '/{id}/image/upload', ['uses' => $class . '@imageUpload', 'as' => $name . '.image.upload.post']);
        Route::post('/' . $name . '/{id}/image/{image_id}/destroy', ['uses' => $class . '@imageDestroy', 'as' => $name . '.image.destroy.post']);
        Route::post('/' . $name . '/{id}/image/{image_id}/main', ['uses' => $class . '@imageMain', 'as' => $name . '.image.main.post']);

        Route::post('/' . $name . '/{id}/text/upload', ['uses' => $class . '@textUpload', 'as' => $name . '.text.upload.post']);
        Route::post('/' . $name . '/{id}/text/save', ['uses' => $class . '@textSave', 'as' => $name . '.text.save.post']);
    }
}
