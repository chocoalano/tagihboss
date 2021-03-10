<div class="modal-header">
    <h4 class="modal-title" id="myLargeModalLabel">Show Users</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <section>
        <div class="form-group row">
            <div class="col-sm-6">
                <div class="form-group has-success">
                    <label class="form-control-label">Userame Activated</label>
                    <input type="text" class="form-control form-control-success" readonly value="{{$user->name}}">
                    <div class="form-control-feedback">Username data from Micro BPR Online</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group has-success">
                    <label class="form-control-label">Email Activated</label>
                    <input type="text" class="form-control form-control-success" readonly value="{{$user->email}}">
                    <div class="form-control-feedback">Email data from Micro BPR Online</div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <div class="form-group has-success">
                    <label class="form-control-label">NIK Activated</label>
                    <input type="text" class="form-control form-control-success" readonly value="{{$user->nik}}">
                    <div class="form-control-feedback">NIK data from Micro BPR Online</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group has-success">
                    <label class="form-control-label">Location Activated</label>
                    <input type="text" class="form-control form-control-success" readonly value="{{$user->location}}">
                    <div class="form-control-feedback">Location data from Micro BPR Online</div>
                </div>
            </div>
        </div>
        @if(!empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group has-success">
                    <label class="form-control-label">Roles Activated</label>
                    <input type="text" class="form-control form-control-success" readonly value="{{ $v }}">
                    <div class="form-control-feedback">Roles data from Micro BPR Online</div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </section>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>