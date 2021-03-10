function datatable(){
	var url = $('#datatables').attr('data-users');
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
                name: 'Username'
            },
            {
                data: 'email',
                name: 'Email'
            },
            {
                data: 'rolename',
                name: 'Roles'
            },
            {
                data: 'nik',
                name: 'NIK'
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
      	$('.login').click(function(){
      		let username = $(".username-micro").val();
      		let password = $(".password-micro").val();
      		const data ={
      			'user':username,
      			'password':password
      		};
      		$.post( $(this).attr("url-post"), data, function(response) {
      			let result=JSON.parse(response);
      			if (result['status']==='success') {
      				$("input[name=name]").val(result['username']);
      				$("input[name=email]").val(result['email']);
      				$("input[name=nik]").val(result['nik']);
      				$("input[name=location]").val(result['id_lokasi']);
      				$("input[name=password]").val(password);
      				$( "#response" ).html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Good Authenticated!</strong> Success syscn data authenticated.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
      			}else{
      				$( "#response" ).html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Unauthenticated!</strong> Username/Password salah!.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
      			}
      		});
      	})
      	$('.button-submit-created').click(function(){
      		var dataPost=$("#users-form").serialize();
      		action($(this).attr("stored"),dataPost,'create');
      	})
      }else if ($param==="edit") {
        $('.button-submit-created').click(function(){
      		var dataPost=$("#users-form").serialize();
      		console.log(dataPost);
      		action($(this).attr("stored"),dataPost,'edit');
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
            datatable();
           }
      });
}
// FUNCTION DATA::ENDED

$(function() {
    datatable();
});