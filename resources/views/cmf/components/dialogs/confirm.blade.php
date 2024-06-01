{{--
<div id="pages-dialogs-confirm" class="dialog">
    <div class="dialog__overlay"></div>
    <div class="dialog__content is-vcentered box" style="max-width: 400px;margin-left: 10px; margin-right: 10px;">
        <h5 class="subtitle">
            Действительно?
        </h5>
        <form class="ajax-form" role="form" method="POST" action="{{ route('confirm') }}"
              data-counter=".admin-table-counter"
              data-list=".admin-table"
              data-list-action=""
              data-form-data=".pagination-form"
              data-callback="closeModalAfterSubmit, refreshAfterSubmit"
        >
            <p class="control" style="text-align: center;">
                <button class="button is-primary inner-form-submit with-loading" type="submit">
                    Да
                </button>
                <button class="dialog__close button" type="button" data-dialog="#pages-dialogs-confirm">
                    Нет
                </button>
            </p>
        </form>
        <div style="position: absolute; top: 10px; right: 10px;">
            <a class="dialog__close icon" data-dialog="#pages-dialogs-confirm">
                <i class="fa fa-times"></i>
            </a>
        </div>
    </div>
</div>
--}}
<div class="modal fade" id="pages-dialogs-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content --inner">
            {{-- добавится отренедеренный шаблон --}}
        </div>
    </div>
</div>
<div class="modal fade" id="pages-dialogs-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="background-color: #fff; width: 50%;">
        <div class="modal-content --inner">
            <form class="ajax-form" role="form" method="POST" action="{{-- подставится нужный --}}"
                  data-counter=".admin-table-counter"
                  data-list=".admin-table"
                  data-list-action=""
                  data-form-data=".pagination-form"
                  data-callback="closeModalAfterSubmit, refreshAfterSubmit"
            >
                <input type="hidden" name="force" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body subtitle">
                    <p class="--text">{{-- text --}}</p>
                    <p class="--subtitle">{{-- subtitle --}}</p>
                </div>

                <div class="modal-footer">
                    @if(isset($indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_SOFT_DELETE]) && $indexComponent[\App\Cmf\Core\Parameters\TableParameter::INDEX_SOFT_DELETE])
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary --soft-delete">Soft Delete</button>
                        <button type="button" class="btn btn-danger --force-delete">Force Delete</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary inner-form-submit">Yes</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
