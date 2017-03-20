$(window).on('load', function(){
    $(".se-pre-con").fadeOut("slow");
});

$(document).ready(function(){
    if($(".manual-flip").length > 0 && $("#listOfPlayers").length > 0) {
        NProgress.start();
        fetchPlayers($(".manual-flip").length);
    }
});

$(".clickable").on('click', function(){
    $(".allFlipCards").hide();
    $("." + $(this).attr('data-club')).show();
});

function fetchPlayers(totalShown) {
    $.ajax({
		async: true,
		url: 'index',
		data:{ size: totalShown },
		type: "POST",
		dataType: "json",
		success: function(json) {
            $("#listOfPlayers").append(json.data);
            if(json.count == 50){
                fetchPlayers($(".manual-flip").length);
                NProgress.set(parseInt($(".manual-flip").length)/650);
            } else {
                $("ul.clubList").show();
                NProgress.done();
            }
		}
	});
}
