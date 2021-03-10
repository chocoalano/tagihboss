$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
$(function() {
    $.get( $('#dropdown-notification').attr('urlCount'), function( x ) {
        let res=JSON.parse(x);
      if (res['count'] > 0) {
            $("#activated-notification").show();
      }else{
            $("#activated-notification").hide();
      }
    });
    $("#dropdown-notification").click(function(e){
        e.preventDefault();
        $.get($(".rendered-notif-badge-data").attr('urlNotifRendered'),function(r){
            $(".rendered-notif-badge-data").html(r);
        })
    })
    $(".dropdown-item").click(function(){
    	var url=$(this).attr("url");
    	var action=$(this).attr("actions");
        if (action==="logout") {
            $.post(url, function(response) {});
            return location.reload();
        }else{
            $.get(url,function(response) {
                console.log(response);
            });
        }
    })
});
function neXt($url){
    $.get($url,function(r){
        $(".rendered-notif-badge-data").html(r);
    })
}
function closedNotif(){
    $("#notification-right-data-rendered").hide();
}
function ShowNotificationDetailData($url,$idNotif){
    $("#bd-example-modal-lg-notification-show-detail-data").modal('show');
    $("#spinner").show();
    $.get($url,{idNotif : $idNotif},function(r){
        $(".rendered-data-bd-example-modal-lg-notification-show-detail-data").html(r);
        $("#spinner").hide();
    })
}