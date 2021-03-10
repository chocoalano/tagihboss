    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Documentation</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section>
                <div class="form-group">
                    <input class="form-control title" type="text" placeholder="Title" name="title" value="{{ $findInfo->title }}">
                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="title"></p></div>
                </div>
                <div class="form-group">
                    <textarea class="textarea_editor form-control border-radius-0 summernote" placeholder="Enter Information text ..." name="information"> {{ strip_tags($findInfo->information) }} </textarea>
                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="information"></p></div>
                </div>
                <div class="form-group">
                    <textarea class="textarea_editor form-control border-radius-0 summernote" placeholder="Enter Attention text ..." name="attention"> {{ strip_tags($findInfo->attention) }} </textarea>
                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="attention"></p></div>
                </div>
                <div class="form-group">
                	
                </div>
                <div class="form-group row">
                	<div class="col-sm">
                		<select class="custom-select2 form-control" name="authorization" style="width: 100%; height: 38px;">
                			<option value="">Select Authorization</option>
                			@foreach ($permission as $k => $v)
                            <option value="{{$v->name}}" {{ ($findInfo->authorization == $v->name) ? 'selected' : '' }}>{{$v->name}}</option>
                            @endforeach
                        </select>
                        <div class="form-control-feedback" style="display:none"><p class="text-danger" id="information"></p></div>
                    </div>
                    <div class="col-sm">
                        <div class="custom-control custom-radio mb-5 d-flex justify-content-center">
                            <input type="radio" id="customRadio1" name="status" class="custom-control-input" value="active" {{ ($findInfo->state=="active") ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customRadio1">Publish</label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="custom-control custom-radio mb-5 d-flex justify-content-center">
                            <input type="radio" id="customRadio2" name="status" class="custom-control-input" value="nonactive" {{ ($findInfo->state=="nonactive") ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customRadio2">Unpublish</label>
                        </div>
                    </div>
                    <div class="form-control-feedback" style="display:none"><p class="text-danger" id="title"></p></div>
                </div>
        </section>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>

