<form id="roles-form" stored="{{ route('roles.store') }}">
    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Create Roles</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control roler-name" type="text" placeholder="Roles Name" name="role">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="table-responsive" href="{{url('/permission-data')}}">
                    <div class="rendered"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Save
        </button>
    </div>
</form>