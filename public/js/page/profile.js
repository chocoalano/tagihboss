$(function(){
	$("#profile-setting-authentication").on('submit',function(e){
		e.preventDefault();
		$.ajax({
			type: 'post',
			url: $(this).attr('actions'),
			data: $(this).serialize(),
			beforeSend: function() {
				$(".spinner-border-sm").show();
			},
			success: function(response) {
				console.log(response);
				if (response.status==='error') {
					printErrorMsg(response.validator);
				}else if (response.status==='badreq') {
					swal({
						title: 'Request Error!',
						text: 'Request Update Unuccessfuly!',
						type: 'error',
						confirmButtonClass: 'btn btn-success'
					})
				}else{
					$(".spinner-border-sm").hide();
					swal({
						title: 'Good job!',
						text: 'Request Update Successfuly!',
						type: 'success',
						confirmButtonClass: 'btn btn-success'
					})
				}
			}
		});
	})
	var link = $('.profile-timeline').attr('url');
	tabelTaskAssigment(link);
	$(document).on('click', '.pagination a', function(event) {
		event.preventDefault();
		var page = $(this).attr('href');
		tabelTaskAssigment(page);
	});
})
function printErrorMsg (msg) {
    $(".form-control-feedback").css('display','block');
    $(".form-group").addClass('form-group has-danger');
    $.each( msg, function( key, value ) {
        $("."+key).addClass("form-control form-control-danger")
        $(".form-control-feedback").find("#"+key).append(value);
    });
}
function tabelTaskAssigment($url){
	$("#spinner").show();
	let linkUrl=$url;
	$.get( linkUrl, function( Responsedata ) {
		$( ".profile-timeline" ).html( Responsedata );
		$("#spinner").hide();
	});
}