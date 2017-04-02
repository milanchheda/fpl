var comparePlayers = [];
$(window).on('load', function(){
    $(".se-pre-con").fadeOut("slow");
});

// ===== Scroll to Top ====
$(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        $('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        $('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
});
$('#return-to-top').click(function() {      // When arrow is clicked
    $('body,html').animate({
        scrollTop : 0                    // Scroll to top of body
    }, 2300);
});

$(document).ready(function(){
    $('#searchPlayers,#searchPlayers2').typeahead({
        onSelect: function(item) {

            comparePlayers.push(item.value);
            if(comparePlayers.length == 2) {
                fetchComparisonData(comparePlayers);
            }
    	},
        ajax: {
                displayField: 'playerName',
                url: 'searchPlayers',
                triggerLength: 2
            }
    });

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

function fetchComparisonData(comparePlayers) {
    $.ajax({
		async: true,
		url: 'fetchComparisonData',
		data:{ players: comparePlayers.join(',') },
		type: "POST",
		dataType: "json",
		success: function(json) {
            // $.each(json, function(k, v){
            //     $.each(v, function(nk, nv){
            //         $("#playerComparison table tbody").append("<tr></tr>");
            //     });
            // });
            // $("#playerComparison")
		}
	});
}
