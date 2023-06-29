// left/right drag
// event order: mpusedown,click

// let catcher = caseStudyTrack.find('a.csLink').eq(0);
let starter = caseStudyTrack.find('a.csLink');
let catcher = caseStudyTrack.find('.dragTrack').eq(0);
let mouseDrag = false, mouseStartX = 0, mouseStartY = 0, currentScroll = 0;

let upHandler = function(e){
	currentScroll = $('.csfwop').scrollLeft();

	catcher.off('mousemove');
	catcher.off('mouseup');
	catcher.off('mouseout');
	mouseDrag = false;
	catcher.hide();
};

let outHandler = function(e){
	currentScroll = $('.csfwop').scrollLeft();

	catcher.off('mousemove');
	catcher.off('mouseup');
	catcher.off('mouseout');
	mouseDrag = false;
	catcher.hide();
};

let moveHandler = function(e){
	let dx = -(e.pageX - mouseStartX);
	if( mouseDrag ) { console.log("caseStudy csLink mousemove e.pageX %s dx %s", e.pageX, dx ); }
	$('.csfwop').scrollLeft( currentScroll + dx );
};

let downHandler = function(e){
	mouseDrag = true;
	catcher.show();
	mouseStartX = e.pageX;
	// mouseStartY = e.pageY;
	currentScroll = $('.csfwop').scrollLeft();
	
	catcher.on('mousemove', moveHandler );
	catcher.on('mouseup', upHandler );
	catcher.on('mouseout', outHandler );
};

starter.on('mousedown', downHandler );

.dragTrack{
	position:absolute;
	left:0px;
	top:0px;
	width:100%;
	height:100%;
	z-index:50;
	display:none;
	background-color:rgba(255, 0, 0, 0.0);
	cursor:grabbing;
}

<div class="dragTrack"></div>