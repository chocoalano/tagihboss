    <div class="modal-header">
        <h4 class="modal-title" id="myLargeModalLabel">Task List</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body">
        <section class="container-fluid justify-content-center">
            <div class="form-group row d-flex justify-content-center">
                <div class="custom-control custom-radio mb-5">
                    <input type="radio" id="customRadio1" name="actradio" class="custom-control-input" value="visit" form="{{url('get-form-tasklist')}}" code="{{ Crypt::encrypt($assigment->task_code) }}">
                    <label class="custom-control-label" for="customRadio1">Visit</label>
                </div>
                <div class="custom-control custom-radio mb-5">
                    <input type="radio" id="customRadio2" name="actradio" class="custom-control-input" value="activity" form="{{url('get-form-tasklist')}}" code="{{ Crypt::encrypt($assigment->task_code) }}">
                    <label class="custom-control-label" for="customRadio2">Activity</label>
                </div>
                <div class="custom-control custom-radio mb-5">
                    <input type="radio" id="customRadio3" name="actradio" class="custom-control-input" value="payment" form="{{url('get-form-tasklist')}}" code="{{ Crypt::encrypt($assigment->task_code) }}">
                    <label class="custom-control-label" for="customRadio3">Payment</label>
                </div>
            </div>
            <div class="form-rendered-chose"></div>
        </section>
    </div>