
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(showPosition);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
function showPosition(position) {
    $("input[name=latitude_tempat_tinggal]").val(position.coords.latitude);
    $("input[name=longitude_tempat_tinggal]").val(position.coords.longitude);
    $("input[name=latitude_jaminan]").val(position.coords.latitude);
    $("input[name=longitude_jaminan]").val(position.coords.longitude);
    fetch('http://www.geoplugin.net/json.gp')
    .then((resp) => {
      if(!resp.ok) {
        console.warn('Cannot fetch location data')
        return
      }
      return resp.json()
    })
    .then((data) => {
      //Check if the location matches with a margin of one degree
      if(Math.abs(position.coords.latitude - data.geoplugin_latitude) < 1 && Math.abs(position.coords.longitude - data.geoplugin_longitude) < 1) {
        $("input[name=latitude_tempat_tinggal]").val(data.geoplugin_latitude);
        $("input[name=longitude_tempat_tinggal]").val(data.geoplugin_longitude);
        $("input[name=latitude_jaminan]").val(data.geoplugin_latitude);
        $("input[name=longitude_jaminan]").val(data.geoplugin_longitude);
      } else {
        alert("Location is probably fake")
      }
    })
}
function refreshcordinate(){
    getLocation();
}

function datatableMaster(){
	var url = $('#datatables-assigment-master').attr('data-assigment');
    $("#datatables-assigment-master").DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            paginate: {
              next: '<i class="ion-chevron-right"></i>',
              previous: '<i class="ion-chevron-left"></i>'
            }
        },
        processing: true,
        serverSide: true,
        "bDestroy": true,
        ajax: url,
        columns: [{
                data: 'DT_RowIndex',
                name: 'No'
            },
            {
                data: 'no_rekening',
                name: 'No. Pinjaman'
            },
            {
                data: 'nama',
                name: 'Nama'
            },
            {
                data: 'dpd',
                name: 'DPD'
            },
            {
                data: 'os_pokok',
                name: 'OS Pokok'
            },
            {
                data: 'action',
                name: 'Act',
                orderable: false,
                searchable: false
            },
        ]
    });
}
function datatablePayment(){
    var url = $('#datatables-assigments-payment').attr('data-assigment');
    $("#datatables-assigments-payment").DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            paginate: {
              next: '<i class="ion-chevron-right"></i>',
              previous: '<i class="ion-chevron-left"></i>'
            }
        },
        processing: true,
        serverSide: true,
        "bDestroy": true,
        ajax: url,
        columns: [{
                data: 'DT_RowIndex',
                name: 'No'
            },
            {
                data: 'task_code',
                name: 'Code'
            },
            {
                data: 'angsuran',
                name: 'Angsuran'
            },
            {
                data: 'total',
                name: 'Total Bayar'
            },
            {
                data: 'sisa_angsuran',
                name: 'Sisa Angsuran'
            },
            {
                data: 'action',
                name: 'Act',
                orderable: false,
                searchable: false
            },
        ]
    });
}
function datatableActivity(){
    var url = $('#datatables-assigments-activity').attr('data-assigment');
    $("#datatables-assigments-activity").DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            paginate: {
              next: '<i class="ion-chevron-right"></i>',
              previous: '<i class="ion-chevron-left"></i>'
            }
        },
        processing: true,
        serverSide: true,
        "bDestroy": true,
        ajax: url,
        columns: [{
                data: 'DT_RowIndex',
                name: 'No'
            },
            {
                data: 'task_code',
                name: 'Code'
            },
            {
                data: 'bertemu',
                name: 'Bertemu'
            },
            {
                data: 'case_category',
                name: 'Case Category'
            },
            {
                data: 'action',
                name: 'Act',
                orderable: false,
                searchable: false
            },
        ]
    });
}
function datatablesVisitTempatTinggal(){
    var url = $('#datatables-visit-tt').attr('data-assigment');
    $("#datatables-visit-tt").DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            paginate: {
              next: '<i class="ion-chevron-right"></i>',
              previous: '<i class="ion-chevron-left"></i>'
            }
        },
        processing: true,
        serverSide: true,
        "bDestroy": true,
        ajax: url,
        columns: [{
                data: 'DT_RowIndex',
                name: 'No'
            },
            {
                data: 'no_rekening',
                name: 'No. Pinjaman'
            },
            {
                data: 'kondisi',
                name: 'Kondisi Properti'
            },
            {
                data: 'created_at',
                name: 'Created At'
            },
            {
                data: 'action',
                name: 'Act',
                orderable: false,
                searchable: false
            },
        ]
    });
}
function datatablesVisitJaminan(){
    var url = $('#datatables-visit-jm').attr('data-assigment');
    $("#datatables-visit-jm").DataTable({
        scrollCollapse: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            paginate: {
              next: '<i class="ion-chevron-right"></i>',
              previous: '<i class="ion-chevron-left"></i>'
            }
        },
        processing: true,
        serverSide: true,
        "bDestroy": true,
        ajax: url,
        columns: [{
                data: 'DT_RowIndex',
                name: 'No'
            },
            {
                data: 'no_rekening',
                name: 'No. Pinjaman'
            },
            {
                data: 'kondisi',
                name: 'Kondisi Properti'
            },
            {
                data: 'created_at',
                name: 'Created At'
            },
            {
                data: 'action',
                name: 'Act',
                orderable: false,
                searchable: false
            },
        ]
    });
}

// FUNCTION DATA::STARTED
function download($url){
    $.get($url,function(response){
        console.log(response);
    })
}
function create($url,$id){
    templateForm($url,'create',$id);
}
function view($url,$param){
    templateForm($url,'view',$param);
}
function edit($url,$param){
    templateForm($url,'edit',$param);
}
function destroy($url,$param){
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success margin-5',
        cancelButtonClass: 'btn btn-danger margin-5',
        buttonsStyling: false
    }).then(function () {
        action($url,'x','destroy',$param);
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal(
                'Cancelled',
                'Your imaginary file is safe :)',
                'error'
                )
        }
    })
}

function templateForm($url,$param,$q){
    // alert($q);
    $("#bd-example-modal-lg").modal('show');
    $("#spinner").show();
    $.get($url, {key:$q}, function( templateResponse ) {
      $( ".rendered-data" ).html( templateResponse );
      $(".spinner-border-sm").hide();
      if ($param==="edit") {
        if ($q==='activity') {
            const jb=$("select[name=janji_byr]").val();
            (jb==='Y')? $('.date-picker-janji-bayar').css("display", "block") : $('.date-picker-janji-bayar').css("display", "none");
            $("select[name=janji_byr]").change(function(){
                ($(this).val()==='Y')? $('.date-picker-janji-bayar').css("display", "block") : $('.date-picker-janji-bayar').css("display", "none");
            })
        }
        $("#form-edit").submit(function(e){
            var url=$(this).attr('url');
            var formData = new FormData($("#form-edit")[0]);
            action(url,formData,'create',$q);
            e.preventDefault();
        })
      }else if ($param==="create") {
        $("input[name=actradio]").click(function(){
            let value=$(this).val();
            $("#spinner").show();
            $.post($(this).attr('form'), {form:$(this).val(), id:$(this).attr('code')}, function( form ) {
                  $( ".form-rendered-chose" ).html( form );
                  if (value==='visit') {
                    getLocation();
                    $("#form-tt").hide();
                    $("#form-jm").hide();
                    $("#button-form-all").hide();
                    $("#button-form-tt").hide();
                    $("#button-form-jm").hide();
                    $('.check-location-map').click(function(e){
                        window.open($(this).attr('link'));
                        e.preventDefault();
                    })
                    // camera option
                    $("#foto-tempat-tinggal-1").click(function(e){
                        take_foto();
                        e.preventDefault();
                    })
                    $("#foto-tempat-tinggal-2").click(function(e){
                        take_foto();
                        e.preventDefault();
                    })
                    $("#foto-tempat-tinggal-3").click(function(e){
                        take_foto();
                        e.preventDefault();
                    })

                    $("#foto-tempat-jaminan-1").click(function(e){
                        take_foto();
                        e.preventDefault();
                    })
                    $("#foto-tempat-jaminan-2").click(function(e){
                        take_foto();
                        e.preventDefault();
                    })
                    $("#foto-tempat-jaminan-3").click(function(e){
                        take_foto();
                        e.preventDefault();
                    })
                    $( "input[name=customRadioVisit]" ).on( "click", function(e){
                        var FormVisit=$(this).val();
                        if (FormVisit==="tt") {
                            $("#form-tt").show();
                            $("#form-jm").hide();
                            $("#button-form-all").hide();
                            $("#button-form-tt").show();
                            $("#button-form-jm").hide();
                        }else if (FormVisit==="jm") {
                            $("#form-tt").hide();
                            $("#form-jm").show();
                            $("#button-form-all").hide();
                            $("#button-form-tt").hide();
                            $("#button-form-jm").show();
                        }else if (FormVisit==="tt&jm") {
                            $("#form-tt").show();
                            $("#form-jm").show();
                            $("#button-form-all").show();
                            $("#button-form-tt").hide();
                            $("#button-form-jm").hide();
                        }else{
                            $("#form-tt").hide();
                            $("#form-jm").hide();
                            $("#button-form-all").hide();
                            $("#button-form-tt").hide();
                            $("#button-form-jm").hide();
                        }
                    });
                    $('.pic-now-wizard').click(function(){
                        var FormVisit=$( "input[name=customRadioVisit]:checked" ).val();
                        Webcam.snap( function(data_uri) {
                          var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
                          let cekCamera1=$("input[name=imageTempatTinggal1]").val();
                          let cekCamera2=$("input[name=imageTempatTinggal2]").val();
                          let cekCamera3=$("input[name=imageTempatTinggal3]").val();
                          let cekCameraJaminan1=$("input[name=imageJaminan1]").val();
                          let cekCameraJaminan2=$("input[name=imageJaminan2]").val();
                          let cekCameraJaminan3=$("input[name=imageJaminan3]").val();

                            if (FormVisit==="tt") {
                                if (cekCamera1 === '') {
                                    $("input[name=imageTempatTinggal1]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-tinggal-1").hide();
                                }else if (cekCamera2 === '') {
                                    $("input[name=imageTempatTinggal2]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-tinggal-2").hide();
                                }else if (cekCamera3 === '') {
                                    $("input[name=imageTempatTinggal3]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-tinggal-3").hide();
                                }
                            }else if (FormVisit==="jm") {
                                if (cekCameraJaminan1 === '') {
                                    $("input[name=imageJaminan1]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-jaminan-1").hide();
                                }else if (cekCameraJaminan2 === '') {
                                    $("input[name=imageJaminan2]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-jaminan-2").hide();
                                }else if (cekCameraJaminan3 === '') {
                                    $("input[name=imageJaminan3]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-jaminan-3").hide();
                                }
                            }else if (FormVisit==="tt&jm") {
                                if (cekCamera1 === '') {
                                    $("input[name=imageTempatTinggal1]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-tinggal-1").hide();
                                }else if (cekCamera2 === '') {
                                    $("input[name=imageTempatTinggal2]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-tinggal-2").hide();
                                }else if (cekCamera3 === '') {
                                    $("input[name=imageTempatTinggal3]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-tinggal-3").hide();
                                }else if (cekCameraJaminan1 === '') {
                                    $("input[name=imageJaminan1]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-jaminan-1").hide();
                                }else if (cekCameraJaminan2 === '') {
                                    $("input[name=imageJaminan2]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-jaminan-2").hide();
                                }else if (cekCameraJaminan3 === '') {
                                    $("input[name=imageJaminan3]").val(raw_image_data);
                                    Webcam.reset();
                                    $("#foto-tempat-jaminan-3").hide();
                                }
                            }else{
                                Webcam.reset();
                            }
                        });
                    })
                    // submit form::started
                    $(".save-all-tt-jm-form-data").on("click",function(){
                        $('form').submit();
                    })
                    $("#form-tempat-tinggal-visit").on("submit",function(e){
                        var formData = new FormData($(this)[0]);
                        let url=$(this).attr("url");
                        action(url,formData,'create',$q);
                        e.preventDefault();
                    })
                    $("#form-jaminan-visit").on("submit",function(e){
                        var formData = new FormData($(this)[0]);
                        let url=$(this).attr("url");
                        action(url,formData,'create',$q);
                        e.preventDefault();
                    })
                    // submit form::ended
                  }else if (value==='activity') {
                    $('.pic-now-wizard').click(function(){
                      take_snapshot($q,'wizard-activity');
                    })
                    const jb=$("select[name=janji_byr]").val();
                    (jb==='Y')? $('.date-picker-janji-bayar').css("display", "block") : $('.date-picker-janji-bayar').css("display", "none");
                    $("select[name=janji_byr]").change(function(){
                        ($(this).val()==='Y')? $('.date-picker-janji-bayar').css("display", "block") : $('.date-picker-janji-bayar').css("display", "none");
                    })
                    $('.submit-now').click(function(){
                      take_foto();
                    })
                  }else if (value==='payment') {
                    $('#saveNasabah').show();
                    $('#saveCollection').hide();
                    var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
                      backgroundColor: 'rgba(255, 255, 255, 0)',
                      penColor: 'rgb(0, 0, 0)'
                    });
                    $("#saveNasabah").on('click',function(e){
                        var data1 = signaturePad.toDataURL().replace(/^data\:image\/\w+\;base64\,/, '');
                        $("#signature64Nasabah").val(data1);
                        $("#signature64Collection").val('');
                        signaturePad.clear();
                        $('#text-signature').text('Collection');
                        $('#saveNasabah').hide();
                        $('#saveCollection').show();
                        swal({
                                title: 'Signature Berhasil Dibuat!',
                                text: 'Signature successfully added!',
                                type: 'success',
                                showCancelButton: true,
                                confirmButtonClass: 'btn btn-success'
                            })
                        e.preventDefault();
                    });
                    $("#saveCollection").on('click',function(e){
                        var data2 = signaturePad.toDataURL().replace(/^data\:image\/\w+\;base64\,/, '');
                        $("#signature64Collection").val(data2);
                        signaturePad.clear();
                        $('#saveNasabah').hide();
                        $('#saveCollection').hide();
                        $('#canvas-signature').hide();
                        swal({
                                title: 'Good job!',
                                text: 'Collection signature successfully added!',
                                type: 'success',
                                showCancelButton: true,
                                confirmButtonClass: 'btn btn-success'
                            })
                        e.preventDefault();
                    });
                    $("#clear").on('click',function(e){
                        signaturePad.clear();
                        $("#signature64Nasabah").val('');
                        $("#signature64Collection").val('');
                        e.preventDefault();
                    });
                    $('.pic-now-wizard').click(function(){
                      take_snapshot($q,'wizard-payment');
                    })
                    $('.submit-now').click(function(){
                      take_foto();
                    })
                  }
                  $("#spinner").hide();
            });
        })
      }else{

      }
      $("#spinner").hide();
    });
}

function action($url,$data,$param,$q){
    // alert($q);
    $("#spinner").show();
    if ($param==='edit') {
        var method='patch';
        var title='Updated';
    }else if ($param==='create') {
        var method='post';
        var title='Created';
    }else if ($param==='destroy') {
        var method='delete';
        var title='Deleted !';
    }
    $.ajax({
           type: method,
           url: $url,
           data: $data,
           contentType: false,
           processData: false,
           beforeSend: function() {
              $(".spinner-border-sm").show();
           },
           success: function(msg) {
            if (msg.status==='error') {
                printErrorMsg(msg.validator);
            }else if (msg.status==='badreq') {
                swal(
                    {
                        title: title,
                        text: msg.msg,
                        type: 'error',
                        confirmButtonClass: 'btn btn-success'
                    }
                )
            }else{
                $(".spinner-border-sm").hide();
                $("#bd-example-modal-lg").modal('hide');
                if ($q==="tt") {
                    datatablesVisitTempatTinggal();
                }else if ($q==="jm") {
                    datatablesVisitJaminan();
                }else if ($q==="activity") {
                    datatableActivity();
                }else if ($q==="payment") {
                    datatablePayment();
                }else{
                    location.reload();
                }
                swal(
                        {
                            title: title,
                            text: msg.msg,
                            type: msg.status,
                            confirmButtonClass: 'btn btn-success'
                        }
                    )
            }
            // console.log(msg);
            $("#spinner").hide();
           }
      });
}

function printErrorMsg (msg) {
    $(".form-control-feedback").css('display','block');
    $(".form-group").addClass('form-group has-danger');
    $.each( msg, function( key, value ) {
        $("."+key).addClass("form-control form-control-danger")
        $(".form-control-feedback").find("#"+key).append(value);
    });
}
// FUNCTION DATA::ENDED

$(function() {
    datatableMaster();
    $('.nav-link').click(function(e) {
        if ($(this).attr('href')==="#master") {
            datatableMaster();
        }else if ($(this).attr('href')==="#visit") {
            $("#spinner").show();
            $.get($(".visit-data-clicked-index").attr('url-data-visit'), function(rendered) {
              $(".visit-rendered").html(rendered);
              datatablesVisitTempatTinggal();
              $("#spinner").hide();
            });
        }else if ($(this).attr('href')==="#activity") {
            datatableActivity();
        }else if ($(this).attr('href')==="#payment") {
            datatablePayment();
        }
     e.preventDefault();
    });
    $(".visit-data-clicked").click(function(e){
        $("#spinner").show();
        const datatables=$(this).attr("funct");
        $.get($(this).attr('url-data-visit'), function(rendered) {
          $(".visit-rendered").html(rendered);
          (datatables==="jm")?datatablesVisitJaminan():datatablesVisitTempatTinggal();
          $("#spinner").hide();
        });
    })
});

function formatRupiah(angka, prefix){
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split           = number_string.split(','),
    sisa            = split[0].length % 3,
    rupiah          = split[0].substr(0, sisa),
    ribuan          = split[0].substr(sisa).match(/\d{3}/gi);
    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function hitungSisaAngsuran(){
    let q=parseInt($("#total_tunggakan").val());
    let x=parseInt($(".angsuran-values").val());
    const hasil=q - x;
    $(".sisa_angsuran").val(hasil);
}
function hitungTotalBayar(){
    let collectfee=parseInt($("input[name=collect_fee]").val());
    let dendaangsuran=parseInt($("input[class=denda-angsuran]").val());
    let denda=parseInt($("input[name=bayar_denda]").val());
    let angsuran=parseInt($(".angsuran-values").val());
    let titipan=parseInt($("input[name=titipan]").val());
    const colfe=( isNaN(collectfee) ) ? 0 : collectfee;
    const dn=( isNaN(denda) ) ? 0 : denda;
    const sdn=( isNaN(dendaangsuran) ) ? 0 : dendaangsuran;
    const ttpn=( isNaN(titipan) ) ? 0 : titipan;
    const hasil=angsuran + dn + colfe + ttpn;
    const hasilsdn=sdn - dn;
    $("input[name=total_bayar_angsuran]").val(hasil);
    $("input[name=sisa_denda]").val(hasilsdn);
}

function take_foto(){
  $("#bd-example-modal-lg-camera").modal('show');
  Webcam.set({
      width: 465,
      height: 340,
      dest_width: 640,
      dest_height: 480,
      align:'center',
      image_format: 'jpeg',
      jpeg_quality: 90,
      force_flash: false
		});
		Webcam.attach( '#my_camera' );
}
function take_snapshot($q,$idpost){
	Webcam.snap( function(data_uri) {
      var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
      $('#photo').val(raw_image_data);
	});
  var formData = new FormData($("#"+$idpost)[0]);
  action($("#"+$idpost).attr("url"),formData,'create',$q);
  Webcam.reset();
  $("#bd-example-modal-lg-camera").modal('hide');
}

function pickFormTT(checkboxTT){
    if (checkboxTT.checked) {
        $("#form-tt").show();
    } else {
        $("#form-tt").hide();
    }
}
function pickFormJM(checkboxJM){
    if (checkboxJM.checked) {
        $("#form-jm").show();
    } else {
        $("#form-jm").hide();
    }
}