<div class="modal-content dialog__content">
    <div class="modal-header">
        <h4 class="modal-title">
            Дополнительная информация
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'info' ? 'active' : '' }}" data-toggle="tab" href="#image-support-info" role="tab">
                        Информация
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane tab-submit {{ $tab === 'info' ? 'active' : '' }}" id="image-support-info" role="tabpanel">
                    <form class="row ajax-form"
                          action="{{ routeCmf($model.'.action.item.post', ['id' => $oImage->id, 'name' => 'imageSupportModalSave']) }}"
                          data-callback="closeSupportModalAfterSubmit"
                    >
                        <input type="hidden" name="id" value="{{ $oImage->id }}">
                        <div class="col-12">
                            <div class="form-group">
                                @include('cmf.content.default.form.default', [
                                    'name' => 'title',
                                    'item' => $oImage,
                                    'field' => [
                                        'title' => 'Заголовок',
                                        'dataType' => App\Cmf\Core\MainController::DATA_TYPE_TEXT,
                                        'default' => $oImage->info['title'] ?? '',
                                    ]
                                ])
                            </div>
                            <div class="form-group">
                                @include('cmf.content.default.form.default', [
                                    'name' => 'description',
                                    'item' => $oImage,
                                    'field' => [
                                        'title' => 'Описание',
                                        'dataType' => App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN,
                                        'default' => $oImage->info['description'] ?? '',
                                    ]
                                ])
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                @include('cmf.content.default.form.default', [
                                    'name' => 'source',
                                    'item' => $oImage,
                                    'field' => [
                                        'title' => 'Источник',
                                        'dataType' => App\Cmf\Core\MainController::DATA_TYPE_MARKDOWN,
                                        'placeholder' => 'Фото: Фамилия Имя или Ссылка',
                                    ]
                                ])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary ajax-link" data-submit-active-tab=".tab-submit">Сохранить</button>
    </div>
</div>
