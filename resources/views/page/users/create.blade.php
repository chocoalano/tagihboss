    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Create Users</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section>
            <div class="login-title">
                <h5 class="text-center text-primary">Login To Core System Sysnc</h5>
            </div>
            <hr />
            <div class="row">
                <div class="col-sm-5">
                    <div class="input-group custom">
                        <input type="text" class="form-control form-control-lg username-micro" placeholder="Username">
                        <div class="input-group-append custom">
                            <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="input-group custom">
                        <input type="password" class="form-control form-control-lg password-micro" placeholder="**********">
                        <div class="input-group-append custom">
                            <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group mb-0">
                        <button class="btn btn-primary btn-lg btn-block login" url-post="{{url('cek-post-sysnc-micro')}}" type="button">Sign In</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="response"></div>
                </div>
            </div>
        </section>
        <hr />
        <section>
            <form id="users-form">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="Username" name="name" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="Email" name="email" readonly>
                    </div>
                    <div class="col-sm-4">
                        <select class="custom-select2 form-control" name="rolename" style="width: 100%; height: 45px;">
                            @foreach ($roles as $key => $value)
                                <option value="{{$value->name}}">{{ ucfirst(strtolower($value->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="NIK" name="nik" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="text" placeholder="Locations ID" name="location" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control roler-name" type="password" placeholder="Password" name="password" readonly>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary button-submit-created" type="button" stored="{{ route('users.store') }}">
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Save
      </button>
    </div>