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

$(".down_vote, .up_vote").click(function() {
    var current = $(this);
    $.ajax({
		async: true,
		url: 'cactus/vote',
		data:{ name: $(this).parents(".blockContainer:first").attr('data-orig-nanme'), type: $(this).attr('class') },
		type: "POST",
		dataType: "json",
		success: function(json) {
            if(json.values) {
                current.parents(".voting_wrapper:first").find(".up_votes").text(json.values.up_vote);
                current.parents(".voting_wrapper:first").find(".down_votes").text(json.values.down_vote);
            } else {
                alert(json.message);
            }
		}
	});
});

$(document).ready(function(){
    sortParticipants('desc');

    $(".sort-order").click(function(){
        sortParticipants($(this).attr('sort-order'));
        if($(this).attr('sort-order') == 'asc'){
            $(".sortText").text("Score Ascending");
            $(this).attr('sort-order', 'desc');
        } else {
            $(".sortText").text("Score Descending");
            $(this).attr('sort-order', 'asc');
        }
    });

    $(".participantGraph").click(function(){
        $(".modal-title").html($(this).attr('data-name'));
        var chartValues = $(this).parents(".blockContainer").attr('data-json');
        console.log(chartValues);
        $('#myModal').modal('show');
        $(function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "theme2",
                animationEnabled: true,
                title: {
                    text: ""
                },
                axisY: {
                    interval:20,
                    includeZero: false
                 },
                data: [
                {
                    type: "line",
                    thickness: 3,
                    dataPoints: $.parseJSON(chartValues)
                }
                ]
            });
            chart.render();
        });
    });

    jQuery('.slider').lbSlider({
        leftBtn: '.sa-left', // left button selector
        rightBtn: '.sa-right', // right button selector
        visible: 5, // visible elements quantity
        autoPlay: false, // autoscroll
        // autoPlayDelay: 10 // delay of autoscroll in seconds
    });

    $(".matches").on('click', function(){
        var getId = $(this).attr('id');
        getId = getId.split('-');
        $("#winningTeamName").text(getId[1]);
        $(".matches").removeClass('active');
        $(this).addClass('active');
        $(".someContainer .panel-heading").removeClass('winners').addClass('losers');
        $(".someContainer " + "." + $(this).attr('id')).addClass("winners").removeClass('losers');
        var winnersCount = $(".someContainer .winners").length;
        var losersCount = $(".someContainer .losers").length;
        $("#ratio").html('Winners: <b>' + winnersCount + ' / </b>Losers: <b>' + losersCount + '</b>');
        // alert($(this).attr('id'));
    });

    $(".compareParticipants").click(function(){
        if($(this).hasClass('fa-plus-square')) {
            $(this).removeClass('fa-plus-square').addClass('fa-minus-square').addClass('compareThis');
            var getCompareCount = $(".compareCount").text();
            if(getCompareCount == '')
                getCompareCount = 0;
            $(".compareCount").text(parseInt(getCompareCount)+1);
        } else {
            $(this).addClass('fa-plus-square').removeClass('fa-minus-square').removeClass('compareThis');
            var getCompareCount = $(".compareCount").text();
            if(getCompareCount == '')
                getCompareCount = 0;
            $(".compareCount").text(parseInt(getCompareCount)-1);
        }
        if(parseInt($(".compareCount").text()) > 1) {
            $("#compareGraph").addClass('makeItRed');
        } else {
            $("#compareGraph").removeClass('makeItRed');
        }
    });

    $("#compareGraph").click(function(){
        if(parseInt($(".compareCount").text()) > 1) {
            var selectedNames = '';
            var selectedJSON = selectedData = '';
            var i = 0;
            var obj = [];
            $(".compareThis").each(function(){
                selectedNames += $(this).parents(".blockContainer").attr('data-name') + ', ';
                selectedJSON = $.parseJSON($(this).parents(".blockContainer").attr('data-json'));
                obj[i] = {
                    type: "line",
                    thickness: 3,
                    showInLegend: true,
                    name: $(this).parents(".blockContainer").attr('data-name'),
                    dataPoints: selectedJSON
                };
                i++;
            });

            selectedNames = selectedNames.substring(0,(selectedNames.length-2));
            var myJsonString = JSON.stringify(obj);
            $(".modal-title").html(selectedNames);
            $('#myModal').modal('show');
            $(function () {
                var chart = new CanvasJS.Chart("chartContainer", {
                    theme: "theme2",
                    animationEnabled: true,
                    title: {
                        text: ""
                    },
                    axisY: {
                        interval:20,
                        includeZero: false
                     },
                    data: $.parseJSON(myJsonString)

                });
                chart.render();
            });
        } else {
            alert("Select atleast 2 participants to check the comparison.");
        }
    });

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

function sortParticipants(sortOrder) {
    $("div.blockContainer").sort(function(a, b) {
        var contentA = parseInt($(a).attr('data-score'));
        var contentB = parseInt($(b).attr('data-score'));
        if(sortOrder == 'asc')
            return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
        else
            return (contentA > contentB) ? -1 : (contentA < contentB) ? 1 : 0;
    }).appendTo($(".someContainer"));

    addFadeColors();
}

function shadeBlend(p,c0,c1) {
    var n=p<0?p*-1:p,u=Math.round,w=parseInt;
    if(c0.length>7){
        var f=c0.split(","),t=(c1?c1:p<0?"rgb(0,0,0)":"rgb(255,255,255)").split(","),R=w(f[0].slice(4)),G=w(f[1]),B=w(f[2]);
        return "rgb("+(u((w(t[0].slice(4))-R)*n)+R)+","+(u((w(t[1])-G)*n)+G)+","+(u((w(t[2])-B)*n)+B)+")"
    }else{
        var f=w(c0.slice(1),16),t=w((c1?c1:p<0?"#000000":"#FFFFFF").slice(1),16),R1=f>>16,G1=f>>8&0x00FF,B1=f&0x0000FF;
        return "#"+(0x1000000+(u(((t>>16)-R1)*n)+R1)*0x10000+(u(((t>>8&0x00FF)-G1)*n)+G1)*0x100+(u(((t&0x0000FF)-B1)*n)+B1)).toString(16).slice(1)
    }
}

function addFadeColors() {
    var score = shadedColor = textColor = '';
    var redColor = '#DE5244';
    var greenColor = '#2DA761';
    $("div.blockContainer").each(function() {
        var getScoreDiff = ''
        score = $(this).data('score');
        if(parseInt(score) > 1000) {
            getScoreDiff = score - 1000;
            getScoreDiff = getScoreDiff/100;
            textColor = '#FFF';
            if(getScoreDiff < 0.7)
                textColor = '#333';
            shadedColor = shadeBlend(1-getScoreDiff,greenColor);
            $(this).find(".panel-heading").css({'background-color': shadedColor, 'color': textColor});
        } else {
            getScoreDiff = 1000 - score;
            getScoreDiff = getScoreDiff/100;
            shadedColor = shadeBlend(1-getScoreDiff,redColor);
            textColor = '#FFF';
            if(1-getScoreDiff > 0.80) {
                textColor = '#333';
            }
            $(this).find(".panel-heading").css({'background-color': shadedColor, 'color': textColor});

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
