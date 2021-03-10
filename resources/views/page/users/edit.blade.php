    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Edit Users</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section>
            <form id="users-form">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="Username" name="name" value="{{ $user->name }}">
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="Email" name="email" value="{{ $user->email }}">
                    </div>
                    <div class="col-sm-4">
                        @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                        <select class="custom-select2 form-control" name="rolename" style="width: 100%; height: 45px;">
                            @foreach ($roles as $key => $value)
                                <option value="{{$value->name}}" {{ ($value->name==$v)?'selected':'' }}>{{ ucfirst(strtolower($value->name)) }}</option>
                            @endforeach
                        </select>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="NIK" name="nik" value="{{ $user->nik }}" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="Locations ID" name="location" value="{{ $user->location }}">
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="password" placeholder="Password" name="password" value="{{ $user->password }}">
                    </div>
                </div>
            </form>
        </section>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary button-submit-created" type="button" stored="{{ route('users.update', Crypt::encrypt($user->id)) }}">
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Save
      </button>
    </div>