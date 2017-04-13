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

    jQuery('.slider').lbSlider({
        leftBtn: '.sa-left', // left button selector
        rightBtn: '.sa-right', // right button selector
        visible: 5, // visible elements quantity
        autoPlay: false, // autoscroll
        // autoPlayDelay: 10 // delay of autoscroll in seconds
    });

    $(".matches").on('click', function(){
        $(".matches").removeClass('active');
        $(this).addClass('active');
        $(".someContainer .panel-heading").removeClass('winners').addClass('losers');
        $(".someContainer " + "." + $(this).attr('id')).addClass("winners").removeClass('losers');
        // alert($(this).attr('id'));
    })

    // We bind a new event to our link
    // $('a.tweet').click(function(e){
    //
    //     //We tell our browser not to follow that link
    //     e.preventDefault();
    //
    //     //We get the URL of the link
    //     var loc = $(this).attr('href');
    //
    //     //We get the title of the link
    //     var title  = encodeURIComponent($(this).attr('title'));
    //
    //     //We trigger a new window with the Twitter dialog, in the middle of the page
    //     window.open('http://twitter.com/share?url=' + loc + '&text=' + title + '&', 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    //
    // });
    //
    // $("#btnSave").click(function() {
    //     var tweetMessage = $(this).attr('data-tweet');
    //     var getContainer = $(this).parents(".front:first");
    //
    //     html2canvas(getContainer, {
    //         onrendered: function (canvas) {
    //             var imagedata = canvas.toDataURL('image/png');
    //     		var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
    //     		// ajax call to save image inside folder
    //     		$.ajax({
    //     			url: 'index/saveImage',
    //     			data: {
    //     			       imgdata:imgdata
    //     				   },
    //     			type: 'post',
    //     			success: function (response) {
    //                     $.ajax({
    //             			url: 'index/tweet',
    //             			data: {
    //                                 tweetMessage:tweetMessage,
    //                                 imagePath:response.imagePath
    //             				   },
    //             			type: 'post',
    //             			success: function (tweetResponse) {
    //
    //                         }
    //                     });
    //     			//    $('#image_id img').attr('src', response);
    //     			}
    //     		});
    //         }
    //     });
    //
    //     // html2canvas(getContainer, {
    //     //     onrendered: function(canvas) {
    //     //         theCanvas = canvas;
    //     //         document.body.appendChild(canvas);
    //     //
    //     //         canvas.toBlob(function(blob) {
	// 	// 			saveAs(blob, "Dashboard.png");
	// 	// 		});
    //     //     }
    //     // });
    //
    // });

    $('#player_1,#player_2').typeahead({
        onSelect: function(item) {

    	},
        ajax: {
            displayField: 'playerName',
            url: 'searchPlayers',
            triggerLength: 2,
            autoSelect: true,
            items: 10
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

$("#getComparisonData").on('click', function(){
    if($("#player_1").attr('player_id') != '' && $("#player_2").attr('player_id') != '') {
        fetchComparisonData($("#player_1").attr('player_id'), $("#player_2").attr('player_id'));
    }
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

function fetchComparisonData(player_1, player_2) {
    $.ajax({
		async: true,
		url: 'fetchComparisonData',
		data:{ players: player_1 + ',' + player_2 },
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
