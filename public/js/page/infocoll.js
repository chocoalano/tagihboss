function datatable(){
	var url = $('#datatables').attr('data-info');
    $("#datatables").DataTable({
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
                name: 'index'
            },
            {
                data: 'title',
                name: 'Title'
            },
            {
                data: 'slug',
                name: 'Slug'
            },
            {
                data: 'state',
                name: 'Status'
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
function create($url){
    templateForm($url,'create');
}
function view($url){
    templateForm($url,'view');
}
function edit($url){
    templateForm($url,'edit');
}
function destroy($url){
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
        action($url,'x','destroy');
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

function templateForm($url,$param){
    $("#bd-example-modal-lg").modal('show');
    $.get($url, function( templateResponse ) {
      $( ".rendered-data" ).html( templateResponse );
      $(".spinner-border-sm").hide();
      if ($param==="create") {
        $('#summernote').summernote();
        $(".button-submit-created").click(function(){
            var url=$(this).attr('stored');
            var dataPost=$("#infocoll-form").serialize();
            action(url,dataPost,'create');
        })
      }else if ($param==="edit") {
        $('#summernote').summernote();
        $(".button-submit-updated").click(function(){
            var url=$(this).attr('stored');
            var dataPost=$("#infocoll-form").serialize();
            action(url,dataPost,'edit');
        })
      }
    });
}

function action($url,$data,$param){
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
                var result=JSON.parse(msg);
                $("#bd-example-modal-lg").modal('hide');
                datatable();
                swal(
                        {
                            title: title,
                            text: msg.msg,
                            type: msg.status,
                            confirmButtonClass: 'btn btn-success'
                        }
                    )
            }
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
    datatable();
});