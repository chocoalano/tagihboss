    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Show Menu</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section>
            <form id="menu-form">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <input class="form-control" type="text" placeholder="Menu Name" name="name" value="{{ $findmenu->name }}" disabled>
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" placeholder="Link" name="link" value="{{ $findmenu->link }}" disabled>
                    </div>
                    <div class="col-sm-3">
                        <select class="custom-select2 form-control" name="type" style="width: 100%; height: 45px;" disabled>
                            <option value="sub-menu" {{ ($findmenu->type=='sub-menu')?'selected':'' }}>Sub Menu</option>
                            <option value="main-menu" {{ ($findmenu->type=='main-menu')?'selected':'' }}>Main Menu</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="custom-select2 form-control" name="masters_id" style="width: 100%; height: 45px;" disabled>
                            <option value="0">Sebagai Main Menu</option>
                            @foreach ($datamenu as $key => $value)
                                <option value="{{$value->id}}" {{ ($findmenu->masters_id==$value->id)?'selected':'' }}>Sebagai sub menu dari {{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <input class="form-control" type="text" placeholder="Icon" name="icon" value="{{ $findmenu->icon }}" disabled>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" placeholder="Authorization" name="authorization" value="{{ $findmenu->authorization }}" disabled>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" placeholder="List" name="list" value="{{ $findmenu->list }}" disabled>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>