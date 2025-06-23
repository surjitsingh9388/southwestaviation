$(document).ready(function() {
   	$(".toggle-side, .left").click(function() {
	    $('.sliding').toggleClass('left');
	});

    $(".activeTble td").click(function(){
    	$(".innerTbl").css({display: "table-row"});
    })
});