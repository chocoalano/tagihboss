    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Edit Menu</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section>
            <form id="menu-form">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input class="form-control name" type="text" placeholder="Menu Name" name="name" value="{{ $findmenu->name }}">
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="name"></p></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input class="form-control link" type="text" placeholder="Link" name="link" value="{{ $findmenu->link }}">
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="link"></p></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select class="custom-select2 form-control type" name="type" style="width: 100%; height: 45px;">
                                <option value="sub-menu" {{ ($findmenu->type=='sub-menu')?'selected':'' }}>Sub Menu</option>
                                <option value="main-menu" {{ ($findmenu->type=='main-menu')?'selected':'' }}>Main Menu</option>
                            </select>
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="type"></p></div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select class="custom-select2 form-control masters_id" name="masters_id" style="width: 100%; height: 45px;">
                                <option value="0">Sebagai Main Menu</option>
                                @foreach ($datamenu as $key => $value)
                                <option value="{{$value->id}}" {{ ($findmenu->masters_id==$value->id)?'selected':'' }}>Sebagai sub menu dari {{$value->name}}</option>
                                @endforeach
                            </select>
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="masters_id"></p></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-control icon" type="text" placeholder="Icon" name="icon" value="{{ $findmenu->icon }}">
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="icon"></p></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-control authorization" type="text" placeholder="Authorization" name="authorization" value="{{ $findmenu->authorization }}">
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="authorization"></p></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input class="form-control list" type="number" placeholder="List" name="list" value="{{ $findmenu->list }}">
                            <div class="form-control-feedback" style="display:none"><p class="text-danger" id="list"></p></div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary button-submit-updated" type="button" stored="{{ route('menus.update', Crypt::encrypt($findmenu->id)) }}">
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Save
      </button>
    </div>