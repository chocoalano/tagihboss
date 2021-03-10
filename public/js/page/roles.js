// FUNCTION DATATABLES::STARTED
function dataTables() {
    var url = $('#datatables').attr('data-roles');
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
                data: 'name',
                name: 'Roles'
            },
            {
                data: 'guard_name',
                name: 'Guard'
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
// FUNCTION DATATABLES::ENDED

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
        dataPermission('');
        $("#roles-form").submit(function(e){
            var dataPost = $("#roles-form").serialize();
            action($("#roles-form").attr("stored"),dataPost,$param);
            e.preventDefault();
        })
      }else if ($param==="edit") {
        dataPermission('');
        $("#roles-form").submit(function(e){
            var dataPost = $("#roles-form").serialize();
            console.log(dataPost);
            action($("#roles-form").attr("stored"),dataPost,$param);
            e.preventDefault();
        })
      }else if ($param==="view") {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switch-btn'));
        $('.switch-btn').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
      }
      // action($url,$param);
    });
}

function dataPermission(url){
    var urlData=(url !='') ? url : $('.table-responsive').attr('href') ;
    $.get(urlData, function( rendered ) {
      $( ".rendered" ).html( rendered );
      var elems = Array.prototype.slice.call(document.querySelectorAll('.switch-btn'));
        $('.switch-btn').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
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
            $(".spinner-border-sm").hide();
            var result=JSON.parse(msg);
            swal(
                {
                    title: title,
                    text: result['msg'],
                    type: result['status'],
                    confirmButtonClass: 'btn btn-success'
                }
            )
            $("#bd-example-modal-lg").modal('hide');
            dataTables();
           }
      });
}
// FUNCTION DATA::ENDED

$(function() {
    dataTables();
});