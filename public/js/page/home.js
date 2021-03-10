$(function(){
	var link = $('.chat-detail').attr('url'); //ini buat nangkep link dari atribut url dalem tag html
	tabelTaskAssigment(link); //ini inisialis function
	$(document).on('click', '.pagination a', function(event) { //ini event paginatenya
		event.preventDefault();
		var page = $(this).attr('href');
		tabelTaskAssigment(page);
	});
})

function tabelTaskAssigment($url){
	$("#spinner").show(); // ini spinner loading
	let linkUrl=$url; //ini nangkep url yg dikirim dari parameter $url
	$.get( linkUrl, function( Responsedata ) {
		$( ".data-task" ).html( Responsedata );
		$("#spinner").hide();
	});
}