<form id="infocoll-form">
    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">View Information</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section>
            <div class="form-group">
                <input class="form-control title" type="text" placeholder="Title" name="title" value="{{ $findInfo->title }}">
                <div class="form-control-feedback" style="display:none"><p class="text-danger" id="title"></p></div>
            </div>
            <div class="form-group">
                <textarea class="textarea_editor form-control border-radius-0" placeholder="Enter text ..." name="information" id="summernote">{{ strip_tags($findInfo->information) }}</textarea>
                <div class="form-control-feedback" style="display:none"><p class="text-danger" id="information"></p></div>
            </div>
            <div class="form-group row">
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
        <button class="btn btn-primary button-submit-updated" type="button" stored="{{ route('infocolls.update', Crypt::encrypt($findInfo->id)) }}">
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Save
        </button>
    </div>
</form>

