<?php
    $idDate = isset($date) ? $date : \Illuminate\Support\Str::random(10);
    $id = 'tmp-' . $idDate;
?>
@if(isset($oQuestion) && !is_null($oQuestion))
    <div class="form-group">
        <label>Вопрос</label>
        <input class="form-control" type="text" name="question[{{$oQuestion->id}}][question]" placeholder="Вопрос" value="{{ $oQuestion->question }}">
    </div>
    <div class="form-group" style="position: relative;">
        @include('cmf.content.default.form.default', [
            'item' => $oQuestion,
            'name' => 'question[' . $oQuestion->id . '][answer]',
            'field' => [
                'title' => 'Ответ',
                'dataType' => App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN,
                'default' => $oQuestion->answer,
            ]
        ])
    </div>
    <div class="form-group">
        @include('cmf.content.default.form.default', [
            'name' => 'question[' . $oQuestion->id . '][priority]',
            'field' => [
                'title' => 'Приоритет',
                'dataType' => App\Cmf\Core\MainController::DATA_TYPE_NUMBER,
                'default' => $oQuestion->priority,
            ]
        ])
    </div>
@else
    <div class="form-group">
        <label>Вопрос</label>
        <input class="form-control" type="text" name="question[{{$id}}][question]" placeholder="Вопрос" value="">
    </div>
    <div class="form-group" style="position: relative;">
        @include('cmf.content.default.form.default', [
            'item' => null,
            'name' => 'question[' . $id . '][answer]',
            'field' => [
                'title' => 'Ответ',
                'dataType' => App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN,
                'default' => '',
            ]
        ])
    </div>
    <div class="form-group">
        @include('cmf.content.default.form.default', [
            'name' => 'question[' . $id . '][priority]',
            'field' => [
                'title' => 'Приоритет',
                'dataType' => App\Cmf\Core\MainController::DATA_TYPE_NUMBER,
                'default' => 0,
            ]
        ])
    </div>
@endif

