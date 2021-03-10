
@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/switchery/switchery.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/sweetalert2/sweetalert2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/jquery-steps/jquery.steps.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('webcam.css')}}">
<script src="{{ asset('webcam.min.js') }}"></script>
<style type="text/css">
    .modal-open {
        overflow: scroll !important;
    }
</style>
@endsection
@section('content')
<div class="min-height-200px">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Assigment Managements</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assigment Managements</li>
                    </ol>
                </nav>
            </div>
       </div>
    </div>
 <!-- Simple Datatable start -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <div class="tab">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-blue" data-toggle="tab" href="#master" role="tab" aria-selected="true">Data Assigment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-blue" data-toggle="tab" href="#visit" role="tab" aria-selected="false">Visit Record</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-blue" data-toggle="tab" href="#activity" role="tab" aria-selected="false">Activity Record</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-blue" data-toggle="tab" href="#payment" role="tab" aria-selected="false">Payment Record</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="master" role="tabpanel">
                        <div class="clearfix mb-20 pd-10">
                            <div class="pull-left">
                                <h4 class="text-blue h4">Assigment Data</h4>
                                <p>Data <code>Assigments</code> from core data on <code>Micro BPR Online System</code>.</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table table stripe hover nowrap table-sm" id="datatables-assigment-master" data-assigment="{{ route('assigments.index') }}">
                                <thead>
                                    <tr>
                                        <th class="table-plus datatable-nosort" width="10">No</th>
                                        <th>No. Pinjaman</th>
                                        <th>Nama</th>
                                        <th>DPD</th>
                                        <th>OS Pokok</th>
                                        <th class="datatable-nosort" width="10">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="visit" role="tabpanel">
                        <div class="clearfix mb-20 pd-10">
                            <div class="pull-left">
                                <p>Visit <code>Assigments</code> assincronous on <code>Micro BPR Online System & SEFIN System</code> integrated.</p>
                            </div>
                            <div class="pull-right">
                                <div class="dropdown">
                                    <a class="btn btn-primary dropdown-toggle visit-data-clicked-index" href="#" role="button" data-toggle="dropdown" aria-expanded="false" url-data-visit="{{url('assigment-visit-index-temp-tgl')}}">
                                        Select data display
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                        <a class="dropdown-item visit-data-clicked" href="#" url-data-visit="{{url('assigment-visit-index-temp-tgl')}}" funct="tt">Data Tempat Tinggal</a>
                                        <a class="dropdown-item visit-data-clicked" href="#" url-data-visit="{{url('assigment-visit-index-jaminan')}}" funct="jm">Data Jaminan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="visit-rendered"></div>
                    </div>
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="clearfix mb-20 pd-10">
                            <div class="pull-left">
                                <h4 class="text-blue h4">Assigment Data</h4>
                                <p>Data <code>Assigments Activity</code> from core data on <code>Micro BPR Online System</code>.</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table table stripe hover nowrap table-sm" id="datatables-assigments-activity" data-assigment="{{ url('assigments-activity-index') }}">
                                <thead>
                                    <tr>
                                        <th class="table-plus datatable-nosort" width="10">No</th>
                                        <th>Code</th>
                                        <th>Bertemu</th>
                                        <th>Case Category</th>
                                        <th class="datatable-nosort" width="10">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="clearfix mb-20 pd-10">
                            <div class="pull-left">
                                <h4 class="text-blue h4">Assigment Data</h4>
                                <p>Data <code>Assigments Payment</code> from core data on <code>Micro BPR Online System</code>.</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table table stripe hover nowrap table-sm" id="datatables-assigments-payment" data-assigment="{{ url('assigments-payment-index') }}">
                                <thead>
                                    <tr>
                                        <th class="table-plus datatable-nosort" width="10">No</th>
                                        <th>Code</th>
                                        <th>Angsuran</th>
                                        <th>Total Bayar</th>
                                        <th>Sisa Angsuran</th>
                                        <th class="datatable-nosort" width="10">Act</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Simple Datatable End -->

<!-- MODAL::STARTED -->
<div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow: auto !important;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="rendered-data"></div>
        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg-camera" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center font-18">
              <h3 class="mb-20">Take Snapshoot</h3>
              <div class="mb-30 justify-content-center">
                <div id="my_camera" style="height:auto;width:auto; text-align:left;"></div>
              </div>
          </div>
          <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-primary pic-now-wizard" data-dismiss="modal">Yes, pick now</button>
              <button type="button" class="btn btn-danger canceled-now-wizard" data-dismiss="modal">No, canceled now</button>
          </div>
        </div>
    </div>
</div>
<!-- MODAL::ENDED -->

<!-- success Popup html Start -->
<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center font-18">
                <h3 class="mb-20">Form Submitted Complicated!</h3>
                <div class="mb-30 text-center"><img src="vendors/images/success.png"></div>
                Whether the data will you send now?
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary submit-now-wizard" data-dismiss="modal">Yes, send now</button>
                <button type="button" class="btn btn-danger canceled-now-wizard" data-dismiss="modal">No, canceled now</button>
            </div>
        </div>
    </div>
</div>
<!-- success Popup html End -->

@endsection
@section('js')
<script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
<!-- buttons for Export datatable -->
<script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('src/plugins/datatables/js/vfs_fonts.js') }}"></script>
<!-- Datatable Setting js -->
<script src="{{ asset('src/plugins/jquery-steps/jquery.steps.js')}}"></script>
<script src="{{ asset('vendors/scripts/datatable-setting.js') }}"></script>
<script src="{{ asset('src/plugins/switchery/switchery.min.js') }}"></script>
<script src="{{ asset('src/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
<script src="{{ asset('src/plugins/sweetalert2/sweet-alert.init.js') }}"></script>
<!-- <script src="{{ asset('vendors/signaturepads/js/signature_pad.umd.js')}}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script src="{{ asset('js/page/tasklist/assigment.js') }}"></script>
@endsection
