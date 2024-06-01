<div class="modal fade" id="pages-dialogs-confirm-force" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="background-color: #fff; width: 50%;">
        <div class="modal-content --inner">
            <form class="ajax-form" role="form" method="POST" action="{{-- подставится нужный --}}"
                  data-counter=".admin-table-counter"
                  data-list=".admin-table"
                  data-list-action=""
                  data-form-data=".pagination-form"
                  data-callback="closeModalAfterSubmit, refreshAfterSubmit"
            >
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary inner-form-submit">Yes</button>
                    <button type="submit" class="btn btn-danger inner-form-submit">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
