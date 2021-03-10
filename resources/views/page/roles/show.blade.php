<form>
    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Show View Roles</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control roler-name" disabled value="{{ $role->name }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Permission</th>
                            <th>Guard</th>
                            <th width="10">Access</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permission as $key => $value)
                        <tr>
                            <td>{{$value->name}}</td>
                            <td>{{$value->guard_name}}</td>
                            @if(!is_null($rolePermissions))
                            <td><input type="checkbox" class="switch-btn" data-color="#0099ff" name="permission[]" value="{{ $value->id }}" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }} disabled></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</form>