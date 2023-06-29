'use strict';

/*
Classes:

BindVideos
	bind and scale any video elements

VimeoVideoLoader
	vimeo video - attach events for load, buffer and hover to vimeo iframe and container

inlineVideoLoader
	embed inline mp4 video auotplay or non autoplay selectable via data attribute

YoutubeVideoLoader
	youtube video loader

AutoVideoScale
	scale inlne mp4 or vimeo iframe to fill container

BuggerScroller
	'who we are' CXM module text block fade in / fade out with scroll
	who we are

GenericScrollHandler
	generic scroll handler used to control some animations - used for lottie CXM chart and group map in 'wo we are' page

ImageLoader
	used to handle image loading for dynamically loaded news/blog posts on 'News' page

QueueSingle
	handle sequential events, one at a time, such as waiting for an image to load before going onto the next item
	'News' page 'load more', 'filter'

Queue
	handle sequential events

NewsLoader
	'News' page load more/filter posts via AJAX/javascript DOM

Counter
	case stduy page awards section - animated values from 0 to specified value

MergeVideoStarter
	'Who we are page MBA + Stack merger video start on scroll down
	'
FuseVideoScroll
	'who we are' page fuse animate video with scroll

PageAnimations
	GSAP animations	

PeopleBios
	people bio overlay
*/

(() => {
	// console.log('classes.js autorun function inside lambda function');
}) ();

/*
$('body').append($('<p></p>').css({
	"position" : "fixed",
	"top" : "20px",
	"left" : "20px",
	"background-color" : "#fff",
	"padding" : "10px",
	"color" : "#000",
	"font-size" : "2rem"
}).attr({"id" : "vvt"}).text("debug") );
$('#vvt').text("this.playing " + (this.playing) ? "true" : "false" );
*/
/* ------------------------------------------------ */
// BindVideos
/* ------------------------------------------------ */

class BindVideos{
	constructor(parameters){
		this.parameters = parameters;

		this.init();
	}
	init(){
		if ( $('.vimeoWrapper').length > 0 ) {
			this.bindVimeo();
		}
		if ( $('.inlineWrapper').length > 0 ) {
			this.bindInline();
		}

		if ( $('.youtubeWrapper').length > 0 ) {
			this.bindYoutue();
		}

		if ( $('.videoWrapper').length > 0 ) {
			this.scaleVideos();
		}
	}
	bindVimeo(){
		/*
		-> VimeoVideoLoader -> source/js/classes.js -> assets/js/classes.min.js
		*/
		let vimeoVideos = [];
		let vimeoWrappers = $('.vimeoWrapper');

		// window.setTimeout(() => {
			vimeoWrappers.each(function(i){
				if ( !$(this).parents('.background').hasClass('videoLoaded') ) {
					vimeoVideos.push( new VimeoVideoLoader({
						"container" : $(this)
					}));
				}

			});
		// },1000);
	}

	bindInline(){
		/*
		-> inlineVideoLoader -> source/js/classes.js -> assets/js/classes.min.js
		*/
		let inlineVideos = [];
		let inlineWrappers = $('.inlineWrapper');

		// window.setTimeout(() => {
			inlineWrappers.each(function(i){
				if ( !$(this).parents('.background').hasClass('videoLoaded') ) {
					inlineVideos.push( new inlineVideoLoader({
						"container" : $(this)
					}));
				}

			});
		// },1000);
	}

	bindYoutue(){
		let youtubeVideos = [];
		let youtubeWrappers = $('.youtubeWrapper');
	}

	scaleVideos(){
		/*
		-> AutoVideoScale -> source/js/classes.js -> assets/js/classes.min.js
		*/

		// scale inline video and vimeo videos to fill container
		let videoWrappers = $('.videoWrapper');
		let scalers = [];
		videoWrappers.each(function(i){
			if ( !$(this).hasClass('scaled') ) {
				scalers.push(new AutoVideoScale({
					"container" : $(this)
				}));

			}
		})
	}
}

/*
let bindVideos = new BindVideos({});
*/

/* ------------------------------------------------ */
// VimeoVideoLoader
/* ------------------------------------------------ */

class VimeoVideoLoader  {
	constructor(parameters) {
		this.parameters = parameters;

		this.container = parameters.container;

		this.init();
	}

	init(){
		let that = this;

		this.pwidth  = ($(window).width() > 992 ? 'desktop' : 'mobile');

		// check to see if the video is in a slick slider carousel
		this.isInSlickSlider = (this.container.parents('.slick-slider').length > 0);

		$(this).css({"border" : "10px solid red"});
		this.format = this.container.attr('data-format'); // vimeo, inline, youtube

		this.mode = this.container.attr('data-mode'); // autoplay, manual, hover
		this.source = this.container.attr('data-source'); // autoplay, manual, hover

		this.iframe = this.container.find('iframe');
		this.player = new Vimeo.Player(this.iframe.get(0));
		this.super = this.container.parents('.background').eq(0);

		this.playing = false;

		/*
		this.player.on('loaded', () => {
			// fade in vimeo iframe and hide static image background
			this.super.addClass('videoLoaded');
		} );
		*/

		switch( this.mode ) {
			case 'autoplay' : {
				// code runs after video has buffered
				this.player.getPaused().then(function(paused) {
				  // `paused` indicates whether the player is paused
				  if ( !paused ) {
				  	that.super.addClass('videoLoaded');	
				  }
				});

				this.player.on('play', () => {
					window.setTimeout(() => {
						this.super.addClass('videoLoaded');
					}
					,10);
				});

				// buffered event needs to be set up as soon as possible
				this.player.on('bufferend', () => {
					// this.super.addClass('videoLoaded');
					this.player.play().then(function(){ }).catch(function(){ });
				});
				// this.player.play();	

			} break;

			case 'manual' : {
				// player object does sem to be firing any 'load' events when set for manual start
				/*
				// canplaythrough
				this.player.on('canplaythrough', () => {
					console.log( "canplaythrough => %s", this.source );
				});				

				// loadeddata
				this.player.on('loadeddata', () => {
					console.log( "loadeddata => %s", this.source );
				});			
				this.player.on('bufferend', () => {
					this.super.addClass('videoLoaded');
					// $('#vvt').text("this.playing " + (this.playing) ? "true" : "false" );
				});
				*/
				// show videoe element whether or  not it has finsihed loading
				this.super.addClass('videoLoaded');
			} break;

			case 'hover' : {
				switch( this.pwidth ){
					case 'desktop' : {
						this.csLink = this.super.parents('.caseStudy').eq(0).find('a.csLink');

						this.csLink.on('mouseover',(event) => {
							this.playing = true;
							this.player.play().then(function(){ }).catch(function(){ });
						});
						this.csLink.on('mouseout',(event) => {
							if ( this.playing ) {
								this.player.pause();
								this.playing = false;
							}
						});

						// hide poster irresepctive of whethre the bufferend event fires
						window.setTimeout(() => {
							this.super.addClass('videoLoaded');
						}
						,500);

						this.player.getPaused().then(function(paused) {
						  // `paused` indicates whether the player is paused
						  if ( !paused ) {
						  	that.super.addClass('videoLoaded');	
						  }
						});

						this.player.on('bufferend', () => {
							if (this.playing) {
								// mouse is over the video panel before it has loaded
								// start the video, then fade in the video
								this.player.on('play', () => {
									window.setTimeout(() => {
										this.super.addClass('videoLoaded');
									}
									,10);
								});

								this.player.play().then(function(){ }).catch(function(){ });	
							} else {
								// mouse is not over the panel
								// pause video
								// console.log(this.player.getPaused());
								this.player.pause();
								this.playing = false;

								// fade in the video
								window.setTimeout(() => {
									this.super.addClass('videoLoaded');
								}
								,10);
							}

						});

					} break;
					case 'mobile' : {
					} break;
				}

			} break;
		}
	}
};

/*

*/

/* ------------------------------------------------ */
// inlineVideoLoader
/* ------------------------------------------------ */

// how to fix 'Uncaught (in promise) DOMException: The play() request was interrupted by a call to pause().' error
// https://developers.google.com/web/updates/2017/06/play-request-was-interrupted
// https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video

class inlineVideoLoader  {
	constructor(parameters) {
		this.parameters = parameters;

		this.container = parameters.container;

		this.init();
	}

	init(){
		this.pwidth  = ($(window).width() > 992 ? 'desktop' : 'mobile');

		// check to see if the video is in a slick slider carousel
		this.isInSlickSlider = (this.container.parents('.slick-slider').length > 0);

		this.format = this.container.attr('data-format'); // vimeo, inline, youtube
		this.mode = this.container.attr('data-mode'); // autoplay, manual, hover
		this.source = this.container.attr('data-source');

		this.target = this.container.find('.pr');
		this.super = this.container.parents('.background').eq(0);

		this.target.append( this.buildVideoTag() );
	}
	buildVideoTag() {
		let that = this;

		// https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video
		// https://developers.google.com/web/updates/2017/06/play-request-was-interrupted

		let autoPlayTag = '<video id="" preload="auto" muted nocontrols playsinline loop data-xephon="7188"></video>';
		let manualPlayTag = '<video preload="auto" controls playsinline data-rah="7188"></video>';

		let newVideoTag = null;
		let newSource = $('<source></source>').attr({
			"type" : 'video/mp4',
			"src" : this.source
		});

		switch( this.mode ) {
			case 'autoplay' : {
				newVideoTag = $(autoPlayTag);
				// start video once it has loaded
				newVideoTag.on('loadedmetadata',function(){
					var video = $(this).get(0);
					video.play().then(function(){ }).catch(function(){ });

					that.super.addClass('videoLoaded');
				});
				// loop video when it ends
				newVideoTag.on('ended',function(){
					$(this).get(0).currentTime = 0;
					$(this).get(0).play().then(function(){ }).catch(function(){ });
				});
				newVideoTag.append( newSource );

			} break;

			case 'manual' : {
				newVideoTag = $(manualPlayTag);

				newVideoTag.on('loadedmetadata',function(){
					that.super.addClass('videoLoaded');
				});

				newVideoTag.append( newSource );
			} break;

			case 'hover' : {
				newVideoTag = $(autoPlayTag);

				switch( this.pwidth ){
					case 'desktop' : {

						this.csLink = this.super.parents('.caseStudy').eq(0).find('a.csLink');
						// play video on hover
						this.csLink.on('mouseover',(e) => {
							// console.log("inline hover -> %s mouseover", this.source);
							let caseStudyPanel = $(e.target).parents('.caseStudy');
							let focusVideo = caseStudyPanel.find('video').get(0);
							focusVideo.play().then(function(){ }).catch(function(){ });
						});

						// pause video when mouse moves off case study item
						this.csLink.on('mouseout',(e) => {
							// console.log("inline hover -> %s mouseout", this.source);
							let caseStudyPanel = $(e.target).parents('.caseStudy');
							let focusVideo = caseStudyPanel.find('video').get(0);
							focusVideo.pause();
						});

						newVideoTag.on('loadedmetadata',() => {
							// console.log("inline hover -> %s loadedmetadata", that.source);
							// that.video = $(this).get(0);
							// that.video.play();

							that.super.addClass('videoLoaded');
						});
					} break;

					case 'mobile' : {
						newVideoTag.on('loadedmetadata',(e) => {
							// console.log("inline hover -> %s loadedmetadata", that.source);
							// that.video = $(this).get(0);
							// that.video.play();
							$(e.target).get(0).play().then(function(){ }).catch(function(){ });
							that.super.addClass('videoLoaded');
						});
					} break;
				}

				// loop video
				newVideoTag.on('ended',function(){
					that.video.currentTime = 0;
					that.video.play().then(function(){ }).catch(function(){ });
				});

				// that.video = newVideoTag.get(0);
				newVideoTag.append( newSource );
			} break;

			// special case for 'light the fuse' animation in 'Who we are' page
			case 'scroll' : {
				newVideoTag = $(autoPlayTag);
				// start video once it has loaded
				newVideoTag.on('loadedmetadata',function(){
					var video = $(this).get(0);
					video.play().then(function(){
						video.pause();
					}).catch(function(){ });
					
					$(this).attr({ "data-duration" : video.duration });
					that.super.addClass('videoLoaded');
				});
				// loop video when it ends
				newVideoTag.on('ended',function(){
					$(this).get(0).currentTime = 0;
					$(this).get(0).play().then(function(){ }).catch(function(){ });
				});
				newVideoTag.append( newSource );

			} break;

			case 'scrollstart' : {
				newVideoTag = $(autoPlayTag);

				switch( this.pwidth ){
					case 'desktop' : {
						// start video once it has loaded

						// newVideoTag.on('loadeddata',(e) => {});
						// newVideoTag.on('play',(e) => {});

						newVideoTag.on('loadedmetadata',(e) => {

							var video = $(e.target).get(0);
							video.play().then(function(){
								// video.pause();
								that.super.addClass('videoLoaded');								
							}).catch(function(){ });
						});
					} break;

					case 'mobile' : {
						// start video once it has loaded
						newVideoTag.on('loadedmetadata',(e) => {
							var video = $(e.target).get(0);
							
							video.play().then(function(){
								video.pause();
								that.super.addClass('videoLoaded');
							}).catch(function(){ });
						});
					} break;
				}

				// loop video when it ends
				newVideoTag.on('ended',function(){
					$(this).get(0).currentTime = 0;
					$(this).get(0).play().then(function(){}).catch(function(){});
				});
				newVideoTag.append( newSource );
			} break;

		}

		return newVideoTag;
	}

}

/*
if ( $('.inlineWrapper').length > 0 ) {
	let inlineVideos = [];
	let inlineWrappers = $('.inlineWrapper');

	inlineWrappers.each(function(i){
		inlineVideos.push( new inlineVideoLoader({
			"container" : $(this)
		}));
	});
}

<div class="inlineWrapper" data-format="inline" data-height="1080" data-width="1920" data-source="<?php echo get_template_directory_uri(); ?>/assets/temp/video/inline_video.mp4" data-autostart="yes">
	<div class="pr"></div>
</div>

<div class="inlineWrapper" data-format="inline" data-height="1080" data-width="1920" data-source="<?php echo get_template_directory_uri(); ?>/assets/temp/video/inline_video.mp4" data-autostart="no">
	<div class="pr"></div>
</div>

*/

/* ------------------------------------------------ */
// YoutubeVideoLoader
/* ------------------------------------------------ */

class YoutubeVideoLoader  {
	constructor(parameters) {
		this.parameters = parameters;

		this.container = parameters.container;

		this.init();
	}

	init(){
		this.format = this.container.attr('data-format'); // vimeo, inline, youtube
		this.mode = this.container.attr('data-mode'); // autoplay, manual, hover
		this.source = this.container.attr('data-source');

		this.target = this.container.find('.pr');
		this.super = this.container.parents('.background').eq(0);
	}
}


/* ------------------------------------------------ */
// AutoVideoScale
/* ------------------------------------------------ */

class AutoVideoScale {
	constructor(parameters) {
		this.parameters = parameters;

		this.container = parameters.container; // .videoWrapepr

		this.init();
	}

	init() {
		// mark videoWrapper with noscale for inline videos (container matches aspect ratio of video)

		this.format = this.container.attr('data-format');
		this.source = this.container.attr('data-source');

		this.noscale = this.container.hasClass('noscale');
		this.scaleToHeight = this.container.hasClass('scaleToheight');
		this.scaleToWidth = this.container.hasClass('scaleTowidth');
		this.isFoldVieo = (this.container.parents('.foldVideo').length > 0 );

		// scale once on page load
		// if( !this.container.hasClass('scaled') ) {
		// START
		// ...
		// END
		// }

		// scale on page load and on window.resize
		// scale collect container dimensions
		// START
			this.container.addClass('scaled');
			
			this.target = this.container.find('.pr');

			this.videoDimensions = {
				"w" : this.container.attr('data-width'),
				"h" : this.container.attr('data-height')
			};
			// vh / vw		

			this.containerDimensions = {
				"w" : this.container.width(),
				"h" : this.container.height()
			};
			// ch / cw

			this.scale();

			$(window).on('resize',() => {
				this.containerDimensions = {
					"w" : this.container.width(),
					"h" : this.container.height()
				};
				this.scale();			
			});
		// END 
	}

	scale() {
		var that = this;
		let pw = 0,ph = 0,scale = 0;

		if ( this.isFoldVieo ) {
			// video 95% width of container
			// scale by width
			scale = (this.containerDimensions.w / (this.videoDimensions.w) );
			scale = scale * 0.95;
			pw = this.videoDimensions.w * scale;
			ph = this.videoDimensions.h * scale;

		} else {
			// scale video to fill comntainer, or just to fit width of container for instances of video in content
			if (!this.noscale) {

				// scale video to fit inside container of any aspect ratio
				scale = (this.containerDimensions.w / this.videoDimensions.w) + 0.01;
				pw = this.videoDimensions.w * scale;
				ph = this.videoDimensions.h * scale;

				if (this.containerDimensions.h > ph ) {
					scale = (this.containerDimensions.h / ph) + 0.01;
					pw = pw * scale;
					ph = ph * scale;
				}
			} else {
				if( this.scaleToHeight ) {
					// scale video to fit height of container and maintain aspect ratio of video
					scale = (this.containerDimensions.h / this.videoDimensions.h);
				}

				if (this.scaleToWidth) {
					// scale video to fit width of container and maintain aspect ratio of video
					scale = (this.containerDimensions.w / this.videoDimensions.w);
				}
				pw = this.videoDimensions.w * scale;
				ph = this.videoDimensions.h * scale;
			}
		}

		this.target.css({"width" : pw + "px", "height" : ph + "px"});
		
		// scale video tag for inline format
		switch( this.format ) {
			case 'vimeo' : {
				// scale iframe for vimeo format
				this.target.find('iframe')
				.css({
					"width" : pw + "px",
					"height" : ph + "px"
				})
				.attr({
					"width" : pw,
					"height" : ph
				});
			} break;
			case 'inline' : {
				// scale video tag for inline format
				this.target.find('video')
				.css({
					"width" : pw + "px",
					"height" : ph + "px"
				})
				.attr({
					"width" : pw,
					"height" : ph
				});
			} break;
		}

	}

}



/*
let videoWrappers = $('.videoWrapper');
let scalers = [];
videoWrappers.each(function(i){
	scalers.push(new AutoVideoScale({
		"container" : $(this)
	}));
})

<div class="videoWrapper vimeoWrapper" data-format="vimeo" data-source="482671041" data-height="727" data-width="1296" data-autostart="no">
	<div class="background">
		<div class="pr">
			<iframe class="vimeo" src="https://player.vimeo.com/video/482671041?autoplay=1&amp;loop=1&amp;autopause=0&amp;controls=0&amp;background=1&amp;color=000000&amp;portrait=1" frameborder="0" allow="autoplay;" title="Royal Mail - Royal Mail Parcels" data-ready="true"></iframe>
		</div>
	</div>
</div>

<div class="videoWrapper  inlineWrapper" data-format="inline" data-height="1080" data-width="1920" data-source="<?php echo get_template_directory_uri(); ?>/assets/temp/video/inline_video.mp4" data-autostart="yes">
	<div class="pr"></div>
</div>
*/


/* ------------------------------------------------ */
// BuggerScroller
/* ------------------------------------------------ */

class BuggerScroller {
	constructor(parameters) {
		this.parameters = parameters;

	this.trigger = parameters.trigger;
	this.phases2 = parameters.phases;

		this.init();
	}

	init() {
		var that = this;

		this.triggerNode = this.trigger;
		this.currentTarget = '';

		// set :
		// this.top, this.height, this.bottom, this.wh
		this.getDimensions();

		// window resize
		// update :
		// this.top, this.height, this.bottom, this.wh
		$(window).on('resize',() => { this.getDimensions(); });

		// window scroll
		$(window).scroll(() => { this.monitor($(window).scrollTop()); });
	}

	template() {
		var that = this;
	}

	getDimensions(){
		var that = this;

		this.top = this.triggerNode.position().top;
		this.height = this.triggerNode.height();
		this.bottom = this.top + this.height;
		this.wh = $(window).height();
	}

	monitor( scrolltop ){
		var that = this;

		/* */
		/*
		// case 1 :
		// top of target moves onto bottom of viewport
		// to
		// bottom of target off of top of viewport

		// scrollTop + 100vh
		if ( (scrolltop + this.wh) < this.top ){
			this.update(0);
		}

		if ( (scrolltop + this.wh) > this.top && (scrolltop < this.bottom) ) {
			let a = this.top - this.wh;
			let b = this.bottom;
			let percent =  (scrolltop - a ) / ( b - a );
			this.update(percent);
		}

		if ( (scrolltop) > this.bottom ){
			this.update(1.0);
		}
		*/
		/* */

		/* */
		/*
		// case 2 :
		// top of target moves onto bottom of viewport
		// to
		// bottom of target moves onto bottom of viewport

		if ( (scrolltop + this.wh) < this.top ){
			this.update(0);
		}

		if ( (scrolltop + this.wh) > this.top && ((scrolltop + this.wh) < this.bottom) ) {
			let a = this.top - this.wh;
			let b = this.bottom - this.wh;
			let percent =  (scrolltop - a ) / ( b - a );
			this.update(percent);
		}

		if ( (scrolltop + this.wh) > this.bottom ){
			this.update(1.0);
		}
		*/
		/* */

		/* */
		// case 3 :
		// top of target moves off top of viewport
		// to
		// bottom of target moves onto bottom of viewport
		if ( (scrolltop) < this.top ){
			this.update(0);
		}

		if ( scrolltop > this.top && ((scrolltop + this.wh) < this.bottom) ) {
			let a = this.top;
			let b = this.bottom - this.wh;

			let percent =  (scrolltop - a ) / ( b - a );

			this.update(percent);
		}

		if ( (scrolltop + this.wh) > this.bottom ){
			this.update(1.0);
		}
		/* */

	}

	update( percent ){
		var that = this;

		let p2 = percent * 100;
		let index = 0;
		while( index < this.phases2.length) {
			let thisPhase = this.phases2[index];
			if (thisPhase.start <= p2 && p2 <= thisPhase.stop) {
				if ( this.currentTarget !== thisPhase.id ) {
					this.currentTarget = thisPhase.id;
					$('#ct').text(this.currentTarget);
					this.activeTarget = $('#' + thisPhase.id);
					// this.activeTarget.css({"left" : "0%"});
					// $('.cbitem').not(this.activeTarget).css({"opacity" : 0, "left" : "-100%"})
					$('.cbitem').not(this.activeTarget).css({"opacity" : 0, "display" : "none"})
				}
				// convert scroll position into opacity
				let range = thisPhase.stop - thisPhase.start;
				let ratio = ( thisPhase.action === 'fadein' ) ? (p2 - thisPhase.start) / range : 1.0 - ((p2 - thisPhase.start) / range);
				this.activeTarget.css({"opacity" : ratio, "display" : "block" });
			}
			index++;
		}

	}
}

/*
let cxmBugger = new BuggerScroller({
	"trigger" : $('.cxmv3'),
	"phases" : [
		{
			"id" :  "cb-c",
			"action" : fadein",
			"start" : 0.0,
			"stop" : 16.6
		},
		{
			"id" :  "cb-c",
			"action" : fadeout",
			"start" : 16.6,
			"stop" : 33.2
		},
		{
			"id" :  "cb-d",
			"action" : fadein",
			"start" : 33.2,
			"stop" : 49.8
		},
		{
			"id" :  "cb-d",
			"action" : fadeout",
			"start" : 49.8,
			"stop" : 66.4
		},
		{
			"id" :  "cb-t",
			"action" : fadein",
			"start" : 66.4,
			"stop" : 85.0
		},
		{
			"id" :  "cb-t",
			"action" : fadeout",
			"start" : 85.0,
			"stop" : 100.0
		}
	]
});
*/

/* ------------------------------------------------ */
// BatchItems
/* ------------------------------------------------ */

class BatchItems  {
	constructor(parameters) {
		this.parameters = parameters;
	}

	init(){
	}
};

/* ------------------------------------------------ */
// GenericScrollHandler
/* ------------------------------------------------ */

class GenericScrollHandler  {
	constructor(parameters) {
		this.parameters = parameters;

		this.trigger = parameters.trigger;
		this.subject = parameters.subject;
		this.animateOnce = parameters.animateOnce;
		this.monitorAction = parameters.monitor;
		this.updateAction = parameters.update;
		this.getDimensionsAction = parameters.getDimensions;

		this.init();
	}

	init(){
		this.animationComplete = false;
		this.state = false;

		this.itemID = this.trigger.attr('id');

		this.getDimensions();

		// set animation state based on current scroll offset
		this.monitor($(window).scrollTop());

		// update dimensions on window resize
		this.resizer = $(window).on('resize.getDimensions',() => { this.getDimensions(); });

		// monitor scrolling and update animation
		this.scroller = $(window).scroll(() => { this.monitor($(window).scrollTop()); });
	}

	getDimensions(){
		if( !this.animateOnce || !this.animationComplete ) {

			this.wh = $(window).height();

			this.getDimensionsAction(this);

			// this.top = this.trigger.position().top;
			// this.height = this.trigger.height();
			// this.bottom = this.top + this.height;
		}
	}

	monitor(scrolltop){
		if( !this.animateOnce || !this.animationComplete ) {
			this.monitorAction(this,scrolltop);			
		}
	}

	update(percent){
		this.updateAction(this,percent);
	}

}

/*
// index people
let pNodes = $('.peopleSlickSlider .item');
let pSet = [];

pNodes.each(function(i){
	pSet.push( new GenericScrollHandler({
		"trigger" : $('.peopleSlickSlider'),
		"subject" : $(this),
		"animateOnce" : false, // animation only runs once
		"getDimenions" : (instance) => {
			
			// get top offets of .item
			// let t1 = $('.peopleSlickSlider').position().top;
			// let t2 = instance.trigger.position().top;
			// instance.top = t1 + t2;

			instance.top = instance.trigger.position().top;
			instance.height = instance.trigger.height();
		},
		"monitor" : (instance,scrolltop) => {

			let percent = 0;

			// ------------------------------------------------
			// single shot animation - add class to each element
			// ------------------------------------------------

			// ------------------------------------------------
			// scrub animation based on scroll position
			// ------------------------------------------------

			// instance.top => top of trigger hits top of viewport
			// instance.top - instance.wh => top of trigger hits bottom of viewport
			// instance.bottom => bottom of trigger hits top of viewport
			// instance.bottom - instance.wh => bottom of trigger hits bottom of viewport

			let start = instance.top - (instance.height * 0.75);
			let end = start + (instance.height * 0.75);

			if ( scrolltop > start && scrolltop < end ) {
				percent =  (scrolltop - start ) / ( end - start );
			}

			if ( scrolltop > end ){
				percent = 1.0;
			}

			$('#dbTop').text(instance.top);
			$('#dbBottom').text(instance.bottom);
			$('#dbHeight').text(instance.height);
			$('#dbScrolltop').text(scrolltop);
			$('#dbStart').text(start);
			$('#dbEnd').text(end);
			$('#dbPercent').text(percent);

			instance.update(percent);
		},
		"update" : (instance,percent) => {

			// ------------------------------------------------
			// scrub animation based on scroll position
			// ------------------------------------------------

			if ( percent > 0.0 && percent < 1.0) {
					instance.trigger.addClass('animating');
			} else {
					instance.trigger.removeClass('animating');
			}

			if ( percent === 1.0) {
				instance.trigger.removeClass('animating');

				// animation only runs once
				if( instance.animateOnce) {
					instance.trigger.addClass('animationComplete');
					instance.animationComplete = true;
				}
			}

			window.requestAnimationFrame(() => {
				instance.subject.css({"opacity" : percent, "top" : Math.floor( (1.0 - percent) * 500 ) + "px"});	
			});

		}
	}));
});
*/


/* ------------------------------------------------ */
// ImageLoader
/* ------------------------------------------------ */

class ImageLoader{
	constructor(parameters) {
		this.parameters = parameters;

		this.class = parameters.class;
		this.parentID = parameters.parentID;
		this.source = parameters.source;
		this.alt = parameters.alt;
		this.loads = parameters.loads;
		this.fails = parameters.fails;

		this.init();
	}

	init(){
		var that = this;

		this.noloadTimeout = window.setTimeout( () => {
			// console.log("following item image failed to load: %s", this.parentID);
			that.fails();
			$('#post-' + this.parentID).addClass('imageFail');
		}, 250);

		this.newImg = $('<img></img>')
		.addClass(this.class)
		.attr({
			"src" : this.source,
			"alt" : this.alt,
			"data-parent" : this.parentID
		})
		.on('load', function(e){
			clearTimeout(that.noloadTimeout);
			// let iparent = $(this).attr('data-parent');
			// $('#post-' + iparent).addClass('ready')
			that.loads();
			// console.log("image %s loaded %s", that.parentID, iparent);
		});
		/*
		arrow function changes value of $(this)
		'this' returns ImageLoader instance
		.on('load',() => {
			clearTimeout(this.noloadTimeout);
			let iparent = $(this).attr('data-parent');
			$('#post-' + iparent).addClass('ready')
			console.log("image %s loaded %s", this.parentID, iparent);
		});
		*/
	}

	serverImage() {
		var that = this;

		return this.newImg
	}
}

/*
let postImage = new ImageLoader({
	"parentID" : parameters.item.id,
	"source" : parameters.item.image,
	"alt" : parameters.item.title,
	"loads" : () => { console.log('loads'); },
	"fails" : () => { console.log('fails'); },
});
let newImage = postImage.serverImage(); // jQuery image object
*/


/* ------------------------------------------------ */
// QueueSingle
/* ------------------------------------------------ */

function QueueSingle(parameters) {
	this.parameters = parameters;

	this.actions = parameters.actions;
	this.settings = parameters.settings;

	this.workers = [];
	this.workerIndex = 0;
	this.workerCount = 0;
	this.running = false;

	this.init();
}

QueueSingle.prototype = {
	"constructor" : QueueSingle,
	"template" : function () {var that = this; },
	
	"init" : function () {
		var that = this;

		// console.log('Queue.init');
		// console.log(this.settings);
	},
	"addItem" : function(item) {
		var that = this;
		this.workers.push(item);
		this.workerCount = this.workers.length;

		return this.workerCount;
	},
	"startQueue" : function() {
		var that = this;

		this.actions.queueStart(this);

		this.startWorker();
		this.running = true;
	},
	"startWorker" : function() {
		var that = this;

		if (this.workerIndex < this.workerCount) {
			this.actions.itemStart(this,this.workers[this.workerIndex]);
		} else {
			this.queueComplete();
		}
		
	},
	"workerComplete" : function() {
		var that = this;

		this.actions.itemComplete(this,this.workers[this.workerIndex]);

		this.workerIndex++;	
		this.startWorker();
	},
	"queueComplete" : function() {
		var that = this;

		this.actions.queueComplete(this);
		this.running = false;
	}
}

/*
// setup
let postQueue = new QueueSingle({
	"settings" : {
		"delay" : 250 // quarter second delay between each person fades in
	},
	"actions" : {
		// run when queue is started
		"queueStart" : function(queue){},

		// run when an item is started
		"itemStart" : function(queue,worker){
			let workerNode = $('article#' + worker.id);

			let portraitImageFull = $('<img></img>').attr({
				"alt" : worker.id,
				"src" : worker.image
			})
			.addClass('full')
			.on('load',function(e){

				let portraitImageThumb = $('<img></img>').attr({
					"alt" : worker.id,
					"src" : worker.thumb
				})
				.addClass('thumb')
				.on('load',function(e){
					// stagger image loading
					worker.delay = window.setTimeout(function(){

						queue.workerComplete();	// signal this item has finished and a new item can be started

					}, queue.settings.delay);

				});
				workerNode.find('.frame a').append( portraitImageThumb )

			});
			workerNode.find('.frame a').append( portraitImageFull )

		},

		// run when an item finishes (image.load)
		"itemComplete" : function(queue,worker){
			let workerNode = $('article#' + worker.id);
			workerNode.removeClass('loading');	
		},

		// run when all items have finished
		"queueComplete" : function(queue){
		}
	}
});

// populate queue
posts.map(function(post){
	postQueue.addItem({
		"id" : post.id,
		"thumb" : post.thumb,
		"image" : post.image
	});
});

// start
posts.startQueue();
*/


/* ------------------------------------------------ */
// QueueItem
/* ------------------------------------------------ */

class QueueItem{
	constructor( parameters ) {
		this.parameters = parameters;

		this.properties = parameters.properties;
		this.parentQueue = parameters.parentQueue;
		this.startAction = parameters.startAction;
		this.completeAction = parameters.completeAction;

		this.init();
	}

	init(){
		this.active = false;
		this.completed = false;
	}

	start(){
		this.active = true;
		this.startAction(this.parentQueue,this);
	}

	complete(){
		this.active = false;
		this.completed = true;
		this.completeAction(this);
		this.parentQueue.workerComplete(this.properties.name);
	}
}

/*
// used within Queue
// this = Queue instance
let newItem = new QueueItem({
	"properties" : thisWorker,
	"parentQueue" : this,
	"startAction" : this.workerStartAction, 
	"completeAction" : this.workerCompleteAction
});
*/

/* ------------------------------------------------ */
// Queue
/* ------------------------------------------------ */

class Queue{
	constructor( parameters ) {
		this.itemDelay = parameters.itemDelay;
		this.workerLimit = parameters.workerLimit;

		// callbacks/actions
		// queue
		this.queueStartAction = parameters.queueStartAction; // queue
		this.queueCompleteAction = parameters.queueCompleteAction; // queue

		// worker
		this.workerStartAction = parameters.workerStartAction; // queue,worker
		this.workerCompleteAction = parameters.workerCompleteAction; // queue,worker

		this.init();
	}

	init() {
		this.items = [];
		this.itemCount = 0;
		this.activeItemsBox = [];
		this.activeItems = 0;
		this.running = false;
	}

	addItems(items) {
		let n = 0;
		while ( n < items.length) {
			this.addItem( items[n] );
			n++;
		}
	}

	addItem(item) {
		item.index = this.items.length;
		this.items.push(item);
		this.itemCount++;
	}

	removeItem(identifier) {}

	initWorker(){
		let thisWorker = this.items.pop(); // get LAST item in items array 

		// -> QueueItem
		let newItem = new QueueItem({
			"properties" : thisWorker,
			"parentQueue" : this,
			"startAction" : this.workerStartAction,
			"completeAction" : this.workerCompleteAction
		});
		this.activeItemsBox.push(newItem);
		this.workerStart(newItem);

		this.activeItems++;
	}

	queueStart() {
		this.queueStartAction(this);

		if ( this.items.length > 0 ) {
			// start initial wave of workers, up to this.workerLimit
			let n = 0;
			while( this.activeItems < this.workerLimit && n < this.itemCount) {
				this.initWorker();
				n++;
			}
			this.running = true;
		}
	}

	queueStop() {}

	queuePause() {}

	queueComplete() {
		this.queueCompleteAction(this);
	}

	workerStart(worker) {
		worker.start();
	}

	workerComplete(name) {
		if( this.running ) {
			let n = 0;
			while( n < this.activeItemsBox.length ) {
				let x = this.activeItemsBox[n];

				if (x.properties.name === name)
					break;
				n++;
			}

			this.activeItemsBox.splice(n, 1);

			// start another worker if there are workers left
			if ( this.items.length > 0 ) {
				this.initWorker();
			} 

			// all queued items completed
			if ( this.items.length === 0 && this.activeItemsBox.length === 0 ) {
				this.running = false;
				this.queueComplete(this);
			}

		}
	}
}

/*
let testQueue = new Queue({
	"itemDelay" : 250,
	"workerLimit" : 8,
	"queueStartAction" : function(queue){
		console.log( "- queueStartAction items: %s", queue.itemCount );
		console.log(" ");
	},
	"queueCompleteAction" : function(queue){
		console.log(" ");
		console.log("**");
		console.log("---- queueCompleteAction START");
		console.log("**");
		console.log(" ");

		let n = 0;
		while(n < queue.activeItemsBox.length) {
			let go = queue.activeItemsBox[n];

			console.log(go.properties.slider.text);
			go.properties.slider.configure({"name" : go.properties.name});
			go.properties.slider.initialised({"index" : go.properties.index});

			n++;
		}

		console.log(" ");
		console.log("**");
		console.log("---- queueCompleteAction END");
		console.log("**");
	},
	"workerStartAction" : function(queue,worker){
		console.log("-- worker start name: %s", worker.properties.name);
		window.setTimeout(function(){
			worker.complete();
		},queue.itemDelay)
	},
	"workerCompleteAction" : function(worker){
		console.log("--- worker complete name: %s", worker.properties.name);
		console.log(" ");
	}
});

let n = 0;
while( n < 6) {
	testQueue.addItem({
		"name" : "worker" + n,
		"slider" : {
			"text" : 'alpha beta',
			"configure" : function(parameters){
				console.log("Alpha %s", parameters.name);
			},
			"initialised" : function(parameters){
				console.log("Beta %s", parameters.index);
			}
		}
	})
	n++;
} 

testQueue.queueStart();
*/


/* ------------------------------------------------ */
// NewsLoader
/* ------------------------------------------------ */

let NewsLoader = function(parameters) {
	this.parameters = parameters;

	this.newsModule = parameters.newsmodule;

	this.init();
}
NewsLoader.prototype = {
	"constructor" : NewsLoader,
	"template" : function () {var that = this; },
	
	"init" : function () {
		var that = this;

		this.state = {
			"visibleitems" : this.newsModule.attr('data-visible'),
			"currentfilter" : this.newsModule.attr('data-current-filter'),
			"showloadmore" : true
		}

		this.constants = {
			"moduleid" : this.newsModule.attr('id'),
			"initialitems" : this.newsModule.attr('data-initial'),
			"pagesize" : this.newsModule.attr('data-pagesize'),
			"availablefilters" : this.newsModule.attr('data-filters'),
			"filterclipper" : this.newsModule.attr('data-filter-clipper'),
			"leaditem" : this.newsModule.attr('data-lead-item'),
			"followitemtarget" : this.newsModule.attr('data-follow-item-target'),
			"followitemclipper" : this.newsModule.attr('data-follow-item-clipper'),
			"loadmorecontrol" : this.newsModule.attr('data-load-more-control'),
			"filtercontrol" : this.newsModule.attr('data-filter-control'),
			"leaditemtemplate" : this.newsModule.attr('data-lead-item-template'),
			"followingitemtemplate" : this.newsModule.attr('data-following-item-template'),
		};

		this.templates = {
			"leadItem" : $('#' + this.constants.leaditemtemplate),
			"followingItem" : $('#' + this.constants.followingitemtemplate)
		};

		this.loadMoreButton = $('#' + this.constants.loadmorecontrol);
		this.filterButtons = $('#' + this.constants.filtercontrol + ' button');

		this.loadMoreButton.on('click', function(e) {
			e.preventDefault();
	
			that.doLoadMore();
		});

		this.filterButtons.on('click', function(e) {
			e.preventDefault();
	
				that.doFilter($(this).attr('data-action'));
		});
	},

	"doLoadMore" : function () {
		var that = this;
			/**/
			// CSS classes
			// .newsitem.ready
			// .newsitem.imageFail
			// this.constants.loadmorecontrol .closed
			// this.constants.followitemclipper .closed

			// request parameters
			// action: 'morenews'
			// this.constants.pagesize
			// this.state.currentfilter
			// this.state.visibleitems

			// DOM
			// this.constants.followitemclipper
			// this.constants.followingitemtemplate
			// this.constants.loadmorecontrol

			$.ajax({
				url: localize_vars.ajaxurl,
				type: "GET",
				data: {
					'action': 'morenews',
					'filter' : this.state.currentfilter,
					'visible' : this.state.visibleitems,
					"pagesize" : this.constants.pagesize
				},
				dataType: 'json',
				success: function (data) {
					// handle response
					that.processLoadMoreResponse(data);
				}
			});

			/**/
	},

	"processLoadMoreResponse" : function (data) {
		var that = this;

		$('#' + this.constants.followitemclipper).removeClass('closed');
		this.updateState([
			{
				"field" : "visibleitems",
				"value" : data.ids
			},
			{
				"field" : "showloadmore",
				"value" : data.loadmore
			}
		]);

		// hide load more if no more articles are available
        if ( !data.loadmore ) {
        	$( '#' + this.constants.loadmorecontrol ).addClass('closed');
        }

		// add new news articles uner following items
		// -> up to 3 x buildFollowingItem({});

		this.buildItemQueue(data.items, this.constants.followitemclipper, 'append');
	},

	"doFilter" : function (filter) {
		var that = this;

		/*
		// .newsitem.ready
		// .newsitem.imageFail

		// request parameters
		// action
		// filter this.state.currentfilter
		// inital page size this.constants.initialitems
		*/

		if ( filter !== this.state.currentfilter ) {
			this.state.currentfilter = filter;

			$.ajax({
				url: localize_vars.ajaxurl,
				type: "GET",
				data: {
					'action': 'filternews',
					'filter' : this.state.currentfilter,
					"initial" : this.constants.initialitems
				},
				dataType: 'json',
				success: function (data) {
					// handle response
					that.processFilterResponse(data);
				}
			});

			/**/
		} else {
			console.log("no action");
		}
	},

	"processFilterResponse" : function (data) {
		var that = this;

		this.updateState([
			{
				"field" : "currentfilter",
				"value" : data.filter
			},
			{
				"field" : "visibleitems",
				"value" : data.ids
			},
			{
				"field" : "showloadmore",
				"value" : data.loadmore
			}
		]);

        // hide ALL news items and load more control while the new articels are being built
        // close filterclipper this.constants.filterclipper 
        $('#' + this.constants.filterclipper).addClass('closed');

        // clear lead item this.constants.leaditem
        $('#' + this.constants.leaditem).find('.newsitem').remove();

        // clear all items under this.constants.followitemtarget
        $('#' + this.constants.followitemtarget).find('.newsitem').remove();

        // clear all items in this.constants.followitemclipper
        $('#' + this.constants.followitemclipper).find('.newsitem').remove();

		// hide load more if no more articles are available
        if ( data.loadmore ) {
        	$( '#' + this.constants.loadmorecontrol ).removeClass('closed');
        } else {
        	$( '#' + this.constants.loadmorecontrol ).addClass('closed');
        }

        // update filter
        let allFilters = $('#' + this.constants.filtercontrol).find('li'); 
        let newActive = $('#' + this.constants.filtercontrol).find("button[data-action='" + this.state.currentfilter + "']").parents('li');
        allFilters.not(newActive).removeClass('active');
        newActive.addClass('active');

        $('#' + this.constants.filterclipper).removeClass('closed');

        // build lead item -> this.constants.leaditem
        // display it as soons as the image loads
        this.buildItem({
			"item" : data.lead,
			"template" : that.templates.leadItem,
			"mode" : "append",
			"target" : $('#' + this.constants.leaditem),
			"callback" : (node) => {

				let delayTitmeout = window.setTimeout(() => {
					node.addClass('ready');
				}, 250);

				that.buildItemQueue(data.items, that.constants.followitemtarget, 'append');
				// go to following items queue
				/*
				*/
			}
        });

        // THEN proceed to following items:

        // queue following items
        // build following items -> this.constants.followitemtarget
        // stagger display display each item as it loads

	},

	"buildItem" : function (parameters) {
		var that = this;

		// let template = this.templates.followingItem.clone();
		let template = parameters.template.clone();
		template.removeClass('template');
		template.addClass('newsitem');
		template.attr({"id" : "post-" + parameters.item.id, "data-post-id" : parameters.item.id});

		let target = parameters.target;
		let externalLogo = null;

		let postImage = new ImageLoader({
			"class" : 'post',
			"parentID" : parameters.item.id,
			"source" : parameters.item.image,
			"alt" : parameters.item.title,
			"loads" : () => {
				// parameters.callback(template); // trigger workerComplete
			}, // this = NewsLoader instance
			"fails" : () => {
				// parameters.callback(template); // trigger workerComplete
			}, // this = NewsLoader instance
		});
		let newImage = postImage.serverImage();

		template.find('h3').html(parameters.item.title);
		template.find('p.synopsis').html(parameters.item.synopsis);
		template.find('p.strikethrough span.date').text(parameters.item.date);
		template.find('p.strikethrough span.category').text(parameters.item.category);

		let imageLink = template.find('figure a');
		imageLink.append( newImage );

		// add external site logo to post
		if ( parameters.item.external_site_logo !== '' ) {
			externalLogo = $('<img></img>')
				.addClass('externalLogo')
				.attr({
					"src" : parameters.item.external_site_logo
				});
			template.find('figure').append(externalLogo);
		}

		// escape HTML special characters in attributes
		// https://stackoverflow.com/questions/11591174/escaping-of-attribute-values-using-jquery-attr/11591276
		var tt = $('<div/>').html(parameters.item.title).text();
		imageLink.attr({"title" : tt});

		if ( parameters.item.has_external_link === 'external' ) {
			imageLink.attr({
				"target" : "_blank",
				"href" : parameters.item.link_url
			});
		} else {
			imageLink.attr({
				"href" : parameters.item.permalink
			});
		}

		// build link
		let link = template.find('a.arrowLink');
		if ( parameters.item.has_external_link === 'external' ) {
			link.attr({
				"target" : "_blank",
				"href" : parameters.item.link_url
			});
			link.find('span').text(parameters.item.link_caption);
		} else {
			link.attr({
				"href" : parameters.item.permalink
			});
			link.find('span').text('read more');
		}

		switch( parameters.mode ) {
			case 'insert-before' : {
				template.insertBefore( "#" + this.constants.followitemclipper );
			} break;
			case 'append' : {
				target.append(template);		
			} break;
		}

		// parameters.callback(template); // trigger workerComplete

		parameters.callback(template);

		return true;
	},

	"buildItemQueue" : function (items, target, mode) {
		var that = this;

		// stagger display 
		this.postQueue = new QueueSingle({
			"settings" : {
				"delay" : 250 // quarter second delay between each person fades in
			},
			"actions" : {
				// run when queue is started
				"queueStart" : function(queue){},

				// run when an item is started
				"itemStart" : function(queue,worker){
					// worker.item
					let newPost = that.buildItem({
						"item" : worker.item,
						"mode" : mode,
						"template" : that.templates.followingItem,
						"target" : $('#' + target),
						"callback" : (node) => {
							// console.log("queue.settings.delay: %s", queue.settings.delay);

							let delayTitmeout = window.setTimeout(() => {
								worker.node = node;
								queue.workerComplete();
							},queue.settings.delay);
						}
					});
				},

				// run when an item finishes (image.load)
				"itemComplete" : function(queue,worker){
					worker.node.addClass('ready');
				},

				// run when all items have finished
				"queueComplete" : function(queue){
				}
			}
		});

		let n = 0;
		while ( n < items.length ) {
			let thisItem = items[n];

			this.postQueue.addItem({
				"id" : thisItem.id,
				"item" : thisItem
			});

			n++;
		}

		this.postQueue.startQueue();

	},

	"updateState" : function (items) {
		var that = this;

		let n = 0;
		while (n < items.length) {
			let thisItem = items[n];

			switch ( thisItem.field ){
				case 'visibleitems' : {
					this.state.visibleitems = thisItem.value;
					this.newsModule.attr({"data-visible" : thisItem.value});
				} break;
				case 'currentfilter' : {
					this.state.currentfilter = thisItem.value;
					this.newsModule.attr({"data-current-filter" : thisItem.value});
				} break;
				case 'showloadmore' : {
					this.state.showloadmore = thisItem.value;
				} break;
			}
			n++;
		}
	},

	"banana" : function (code) {
		var that = this;
		console.log("NewsLoader banana: '%s", code);
	}
}

/*
let newsPageLoader = new NewsLoader({
	"filter" : ",
	"loadmore" : ",
	
});
*/


/* ------------------------------------------------ */
// Counter
/* ------------------------------------------------ */

class Counter{
	constructor(parameters){
		this.parameters = parameters

		this.node = parameters.node;
		this.max = parameters.max;

		this.init();
	}

	init(){
		// get type: integer or float
		// int /[0-9]+/
		// float /[0-9]+\.[0-9]+/
		// float with comma seperatoers in integer part [0-9\,]+\.[0-9]+

		const intRegExp = /[0-9]+/;
		const float1RegExp = /[0-9]+\.[0-9]+/;
		
		let matches = null;
		matches = this.max.match(intRegExp);
		if ( matches ) {
			this.type = 'int';
			this.n = parseInt(this.max,10);
		}

		matches = this.max.match(float1RegExp);
		if ( matches ) {
			this.type = 'float';
			this.n = parseFloat(this.max,10);
		}

		this.node.text('0');
	}

	update(percent){

		switch ( this.type ) {
			case 'int' : {
				this.node.text( Math.ceil(this.n * percent) );	
			} break;
			case 'float' : {
				this.node.text( (this.n * percent).toLocaleString('en-EN',{minimumFractionDigits:1, maximumFractionDigits:1})  );	
			} break;
		}

	}
}

/*
let counters = [];
let points = $('.case-study-awards .items .point');
points.each(function(i){
	counters.push(new Counter({
		"node" : $(this).find('.value'),
		"max" : $(this).attr('data-value-max')
	}));
});

<div class="col-md-3 point" data-value-max="25">
	<h4><span class="value">25</span>k</h4>
	<p>Leads</p>
</div>
<div class="col-md-3 point" data-value-max="3.2">
	<h4><span class="value">3.2</span>k</h4>
	<p>Incremental annual sales</p>
</div>
*/


/* ------------------------------------------------ */
// MergeVideoStarter
/* ------------------------------------------------ */

class MergeVideoStarter{
	constructor(parameters){
		this.target = parameters.target;
		this.video = parameters.video;

		this.init();
	}
	init(){
		let that = this;
	}
	start(){

	}
}

/*
*/


/* ------------------------------------------------ */
// FuseVideoScroll
/* ------------------------------------------------ */

class FuseVideoScroll{
	constructor(parameters){
		this.target = parameters.target;
		this.video = parameters.video;

		this.init();
	}
	init(){
		let that = this;

		this.ready = false;

		$(this.video).on('loadedmetadata',function(){
			var video = $(this).get(0);
			that.ready = true;
			that.duration = video.duration;
			that.videoElement = video;
		});
	}
	scroll(percent){
		if (percent > 0.0 && percent < 1.0 && this.ready) {
			// console.log(this.duration * percent);
			window.requestAnimationFrame(() => {
				this.videoElement.currentTime  = this.duration * percent;	
			});
		}
	}
}

/*
let fuseVideoAnimation = new FuseVideoScroll({
	"video" : ".fuseAnimation"
});
*/


/* ------------------------------------------------ */
// PageAnimations
/* ------------------------------------------------ */

class PageAnimations{
	constructor(parameters) {
		this.parameters = parameters;

		this.init();
	}

	init() {
		let that = this;
	}

	apply() {
		let that = this;

		// add case study and news/blog post items
		// case study: case-study-banner, case-study-intro, case-study-single_column_text, case-study-fullwidth_image, case-study-awards, case-study-fullwidth_video, case-study-image_carousel, case-study-two_column_media
		// news post : postTitle, singlePost, otherPosts
		const types = /(twoColumnMixed|sharedCaseStudies|latestClients|latest-news|people|homeValues|singleColumn|fuse|tools|cxmv3|nimble|group|twoColumnParallax|otherPosts|trinity|awards|fullWidthImage|case-study-banner|case-study-intro|case-study-single_column_text|case-study-fullwidth_image|case-study-awards|case-study-fullwidth_video|case-study-image_carousel|case-study-two_column_media|singlePost|valuesBlocks)/;

		const boxes = gsap.utils.toArray('.module');
		boxes.forEach(box => {

			let moduleClass = box.getAttribute('class');

			let matches = moduleClass.match(types);
			if ( matches ) {
				let moduleType = matches[0];

				if ( moduleType === 'homeBanner' || moduleType === 'pageBanner' ) {

				} else {
					console.log( "box: '%s'", moduleType );

					switch ( moduleType ) {

						case 'twoColumnMixed' : {
							let thisBox = $(box);

							// text column
							let textColumn = thisBox.find('.textColumn');
							// image column
							let imageColumn = thisBox.find('.imageColumn');

						  gsap.to(textColumn, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + halfheight, // top half way up screen
						    	"end" : 'bottom bottom', // bototm hits bototm of screen
					        markers : false,
						      trigger: box,
						      scrub: false,
						    }
						  })

						  gsap.to(imageColumn, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + halfheight, // top half way up screen
						    	"end" : 'bottom bottom', // bototm hits bototm of screen
						      trigger: box,
						      scrub: false,
						    }
						  })
						} break;

						case 'sharedCaseStudies' : {
							let thisBox = $(box);

							// 4th October desing amends
							// home page - carousel .carouselLayout
							// work page - grid .gridLayout
							
							/*
							batchAnimation('.sharedCaseStudies .caseStudy', 6, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
							*/

						} break;

						case 'latestClients' : {
							// let thisBox = $(box);

							this.batchAnimation('.latestClients .item', 6, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
						} break;

						case 'latest-news' : 
						case 'otherPosts' : {
							let thisBox = $(box);

							this.batchAnimation('.newsitem', 3, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
						} break;

						case 'people' : {
							let thisBox = $(box);

							// different behaviour on home page and 'People' page

							if ( thisBox.hasClass('peopleSlickSlider') ) {

								// interferes with slickslider animation
								/*
								batchAnimation('.people .item .outer .frame figure', 3, {
									"e1" : {
										autoAlpha: 1,
										y:-500,
										stagger: 0.15,
										overwrite: true
									}
								});
								*/
							} else {

								// intro header
								let headrSub = thisBox.find('.teamIntro');
							  gsap.to(headrSub, { 
							    y: -500,
							    autoAlpha: 1,
							    scrollTrigger: {
							    	"start" : 'top bottom',
							    	"end" : 'bottom bottom',
						        // "start" : () => 'top-=' + halfheight,
						        // "end" : () => 'bottom-=' + winheight,
							      trigger: box,
							      scrub: false,
							    }
							  })

								this.batchAnimation('.people .item', 3, {
									"e1" : {
										autoAlpha: 1,
										y:-500,
										stagger: 0.15,
										overwrite: true
									}
								});
							}
						} break;
						
						case 'singleColumn' : {
							/*
							let thisBox = $(box);

							let textColumn = thisBox.find('.ba');
							let th = textColumn.height();
						  gsap.to(textColumn, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + halfheight, // top half way up screen
						    	"end" : () => 'top-=' + (halfheight - th),
					        // "start" : () => 'top-=' + halfheight,
					        // "end" : () => 'bottom-=' + winheight,
						      trigger: box,
						      scrub: false,
						      markers : false
						    }
						  })
						  */

						} break;

						case 'fuse' : {
							// console.log('fuse classes.js 2180');
						} break;

						case 'tools' : {
							let thisBox = $(box);

							// Values page trinity module

							// Pause an element while vertical scrolling -> animate something in the element with scroll
							// 'paused' element
							// .paused

							/*
							-> TrinityScrollMonitor -> source/js/classes.js -> assets/js/classes.min.js
							*/

							// window.setTimeout(function(){
								/*
								const pausedModules = $('section.tools');

								if ( pausedModules.length > 0 ) {
									// const range  = 1000; // 1000px 'pause' in scrolling to accomodate animation
									let thisScrollMonitors = [];
									// pausedModules.each(function(i) {
										let toolsItem = pausedModules.eq(0);
										let pauseTop = toolsItem.position().top;
										let pauseHeight = toolsItem.height();
										let range = pauseHeight * 5.5;
										let toolMonitor = new TrinityScrollMonitor({
											"target" : toolsItem,
											"top" : pauseTop,
											"height" : pauseHeight,
											"range" : range 
										});

										// toolMonitor
										$('.nextItem').on('click',function(e){
											toolMonitor.current++;
											let of = ( (toolMonitor.b - toolMonitor.a) * (toolMonitor.current * 0.21) ) + toolMonitor.a;
											window.scrollTo(0, of );
										});

										$('.toolsProgress li').on('click',function(e){
											let panel = $('.toolsProgress li').index($(this));

											if (panel !== toolMonitor.current) {
												toolMonitor.current = panel;
												let of = ( (toolMonitor.b - toolMonitor.a) * (toolMonitor.current * 0.21) ) + toolMonitor.a;
												window.scrollTo(0, of );
											}
										});
									// });
								}
								*/

								
							// },1000);

						} break;

						case 'cxm' : {
							let thisBox = $(box);


							// -> BuggerScroller
						} break;

						case 'group' : {
							let thisBox = $(box);
							/*
							batchAnimation('.group .mapgraph, .group .msqinfo', 3,{
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
							*/
						} break;

						case 'twoColumnParallax' : {
							let thisBox = $(box);

							this.batchAnimation('.twoColumnParallax .image, .twoColumnParallax .text', 2, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});

						} break;

						case 'valuesBlocks' : {
							let thisBox = $(box);

							this.batchAnimation('.valuesBlocks .image, .valuesBlocks .text', 2, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});

						} break;

						case 'trinity' : {
							console.log('trinity');
							let thisBox = $(box);

						} break;

						case 'awards' : {
							let thisBox = $(box);

							// intro header
							let headrSub = thisBox.find('.introbox');
							let th = headrSub.height();
						  gsap.to(headrSub, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + (winheight * 0.75), 
						    	"end" : () => 'top-=' + (halfheight - th),
						      trigger: box,
						      scrub: false,
						      markers : false
						    }
						  })

							this.batchAnimation('.awards .award', 3, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
						} break;

						case 'fullWidthImage' : {
							let thisBox = $(box);

						} break;

						case 'case-study-banner' : {
							let thisBox = $(box);

						} break;

						case 'case-study-intro' : {
							let thisBox = $(box);

							let blocks = thisBox.find('p');
						  gsap.to(blocks, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + (winheight + 250), 
						    	"end" : () => 'top-=' + (winheight),
						      trigger: box,
						      scrub: false,
						      markers : false
						    }
						  })

						} break;

						/*
						case 'case-study-single_column_text' : {
							let thisBox = $(box);

							// h3,h4,p
							let copyAnimations = [];
							let blocks = thisBox.find('h3, h4, p');

							blocks.each(function(i){
								let thisItem = $(this);
								let th = thisItem.height();

								copyAnimations.push(gsap.to(thisItem, { 
							    y: -500,
							    autoAlpha: 1,
							    scrollTrigger: {
							    	"start" : () => 'top-=' + (winheight+250), 
							    	"end" : () => 'top-=' + ((winheight+250) - 250),
							      trigger: thisItem,
							      scrub: false,
							      markers : false
							    }
							  }));
							  
							});

						} break;
						*/
						case 'case-study-fullwidth_image' : {
							let thisBox = $(box);

							let img = thisBox.find('img');
							let imgWidth = img.attr('width'); 
							let imgHeight = img.attr('height'); 
							let tw = thisBox.find('figure').width();
							let scale = tw / imgWidth;
							let nw = imgWidth * scale;
							let nh = imgHeight * scale;

							thisBox.find('figure').attr({
								"width" : nw,
								"height" : nh
							});

							// console.log("iw %s ih %s tw %s scale %s nw %s nh %s", imgWidth, imgHeight, tw, scale, nw, nh);

							$(window).on('resize',() => {
								let img = thisBox.find('img');
								let imgWidth = img.attr('width'); 
								let imgHeight = img.attr('height'); 
								let tw = thisBox.find('figure').width();
								let scale = tw / imgWidth;
								let nw = imgWidth * scale;
								let nh = imgHeight * scale;

								thisBox.find('figure').attr({
									"width" : nw,
									"height" : nh
								});

								// console.log("iw %s ih %s tw %s scale %s nw %s nh %s", imgWidth, imgHeight, tw, scale, nw, nh);
							});

							// let th = thisBox.height();
							let th = nh;

							let blocks = thisBox.find('figure');
						  gsap.to(blocks, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + (winheight + (th / 2)), 
						    	"end" : () => 'top-=' + ((winheight + (th / 2)) - 250),
						      trigger: box,
						      scrub: false,
						      markers : false
						    }
						  })
						} break;

						case 'case-study-awards' : {
							let thisBox = $(box);
							// h2,h3
							// batch: .items .point

							/*
							batchAnimation('.case-study-awards .point', 2, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
							*/

						  // ---------- ** ----------
						  // Counter -->
						  // ---------- ** ----------

							let counters = [];
							let points = $('.case-study-awards .items .point');
							points.each(function(i){
								counters.push(new Counter({
									"node" : $(this).find('.value'),
									"max" : $(this).attr('data-value-max')
								}));
							});

						  // ---------- ** ----------
						  // GenericScrollHandler -->
						  // ---------- ** ----------

							let peopleAnimation = new GenericScrollHandler({
								"trigger" : $('.case-study-awards'),
								"subject" : $('.case-study-awards'),
								"animateOnce" : true, // animation only runs once
								"getDimensions" : (instance) => {
									instance.top = instance.trigger.position().top;
									instance.height = instance.trigger.height();
									instance.bottom = instance.top + instance.height;

									instance.start = instance.top - (instance.wh * 0.75);
									instance.end = instance.bottom - (instance.wh * 0.5);

									/*
									// start/end markers
									$('body').append($('<div></div>').css({
										"position" : "absolute",
										"top" : Math.floor(instance.start) + "px",
										"left" : "0px",
										"z-index" : "1000",
										"display" : "block",
										"height" : "10px",
										"width" : "100%",
										"background-color" : "#0f0"
									}))
									$('body').append($('<div></div>').css({
										"position" : "absolute",
										"top" : Math.floor(instance.end) + "px",
										"left" : "0px",
										"z-index" : "1000",
										"display" : "block",
										"height" : "10px",
										"width" : "100%",
										"background-color" : "#f00"
									}))
									*/
								},
								"monitor" : (instance,scrolltop) => {
									if ( scrolltop < instance.start ) {
										instance.update(0.0);	
									}

									if ( scrolltop > instance.start && scrolltop < instance.end ) {
										let percent =  (scrolltop - instance.start ) / ( instance.end - instance.start );
										instance.update(percent);	
									}
									
									if (scrolltop >= instance.end) {
										instance.update(1.0);
									}
								},
								"update" : (instance,percent) => {
									// fade in
									points.css({
										"opacity" : percent,
										"top" : Math.floor((1.0 - percent) * 250) + "px" 
									});

									// animate number
									counters.map(function(counter){
										counter.update(percent);
									}, this);
								}
							});

						} break;

						case 'case-study-fullwidth_video' : {
							let thisBox = $(box);
							let th = thisBox.height();

							let blocks = thisBox.find('.videoBox');
						  gsap.to(blocks, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
						    	"start" : () => 'top-=' + (winheight + (th / 2)), 
						    	"end" : () => 'top-=' + ((winheight + (th / 2)) - 250),
						      trigger: box,
						      scrub: false
						    }
						  })
						} break;

						case 'case-study-image_carousel' : {
							/*
							let thisBox = $(box);

							let th = thisBox.height();

							let mediaAnimations = [];
							let blocks = thisBox.find('.slick-slider');

							blocks.each(function(i){
								let thisItem = $(this);
								let th = thisItem.height();

								mediaAnimations.push(gsap.to(thisItem, { 
							    y: -500,
							    autoAlpha: 1,
							    scrollTrigger: {
							    	"start" : () => 'top-=' + (winheight + (th / 2)), 
							    	"end" : () => 'top-=' + ((winheight + (th / 2)) - 250),
							      trigger: box,
							      scrub: false
							    }
							  }));
							});
							*/
						} break;

						case 'case-study-two_column_media' : {
							let thisBox = $(box);
							let th = thisBox.height();
							let mediaAnimations = [];
							let blocks = thisBox.find('.media');

							blocks.each(function(i){
								let thisItem = $(this);
								let th = thisItem.height();

								mediaAnimations.push(gsap.to(thisItem, { 
							    y: -500,
							    autoAlpha: 1,
							    scrollTrigger: {
							    	"start" : () => 'top-=' + (winheight + (th / 2)), 
							    	"end" : () => 'top-=' + ((winheight + (th / 2)) - 250),
							      trigger: box,
							      scrub: false
							    }
							  }));
							});

						} break;

						case 'singlePost' : {
							let thisBox = $(box);

							let copyAnimations = [];
							let blocks = thisBox.find('.content p, .content figure');

							blocks.each(function(i){
								let thisItem = $(this);
								let th = thisItem.height();

								copyAnimations.push(gsap.to(thisItem, { 
							    y: -500,
							    autoAlpha: 1,
							    scrollTrigger: {
							    	"start" : () => 'top-=' + (winheight+250), 
							    	"end" : () => 'top-=' + ((winheight+250) - 250),
							      trigger: thisItem,
							      scrub: false,
							      markers : false
							    }
							  }));
							  
							});

						} break;

					}
				};
			}
		});

		this.lottieScroller();
	}

	batchAnimation( items, batchSize, animation ){

		// https://codepen.io/GreenSock/pen/823312ec3785be7b25315ec2efd517d8

		// usage:
		batch(items, {
		  interval: 0.1, 
		  batchMax: batchSize,   

		  onEnter: batch => gsap.to(batch, animation.e1),
			start : () => 'top-=' + (winheight + 480),
			end : () => 'top-=' + winheight,
			markers : false
		});

		// the magical helper function (no longer necessary in GSAP 3.3.1 because it was added as ScrollTrigger.batch())...
		function batch(targets, vars) {

		  let varsCopy = {},
		      interval = vars.interval || 0.1,
		      proxyCallback = (type, callback) => {
		        let batch = [],
		            delay = gsap.delayedCall(interval, () => {callback(batch); batch.length = 0;}).pause();
		        return self => {
		          batch.length || delay.restart(true);
		          batch.push(self.trigger);
		          vars.batchMax && vars.batchMax <= batch.length && delay.progress(1);
		        };
		      },
		      p;

		  for (p in vars) {
		    varsCopy[p] = (~p.indexOf("Enter") || ~p.indexOf("Leave")) ? proxyCallback(p, vars[p]) : vars[p];
		  }
		  
		  gsap.utils.toArray(targets).forEach(target => {
		    let config = {};
		    for (p in varsCopy) {
		      config[p] = varsCopy[p];
		    }
		    config.trigger = target;

		    ScrollTrigger.create(config);
		  });
		}
	}

	lottieScroller() {
		// who we are 'CXM' mdule map animations
		if ( $('section.cxmv3').length > 0 ){
			let target = $('.lst-cxm');
			let source = target.attr('data-source');

			let cxmAnimationFrames = lottie.loadAnimation({
				container: target.get(0), 
				renderer: 'svg',
				loop: false,
				autoplay: false,
				path: source 
			});

			let cxmGraphAnimation = new GenericScrollHandler({
				"trigger" : $('.cxmv3'),
				"subject" : $('.cxmv3 figure'),
				"animateOnce" : false, // animation only runs once
				"getDimensions" : (instance) => {
					instance.top = instance.trigger.position().top;
					instance.height = instance.trigger.height();
					instance.bottom = instance.top + instance.height;

					instance.start = instance.top - (instance.wh / 2); // top of module halfway up browser window
					instance.end = instance.bottom - (instance.wh / 2); // bottom of module halfway up browser window
				},
				"monitor" : (instance,scrolltop) => {
					let percent = 0;

					instance.state = false;

					// not reach CXM block yet
					if ( scrolltop < instance.start ) {
						// first frame
						instance.update(0.0);
					}

					// CXM block visible
					if ( scrolltop > instance.start && scrolltop < instance.end ) {

						let percent =  (scrolltop - instance.start ) / ( instance.end - instance.start );
						instance.state = true;
						instance.update(percent);	
					}

					// scrolled past CXM block
					if ( scrolltop > instance.end ) {
						// last frame
						instance.update(1.0);
					}
				},
				"update" : (instance,percent) => {

					/* this is the important part */
					cxmAnimationFrames.goToAndStop(percent * (cxmAnimationFrames.totalFrames - 1), true);			
				}
			});
		}

		// if ( $('.lst-fuse').length > 0 ) {}

		// 'Who we are page' tools section
		if ( $('section.tools').length > 0 ) {
			// check for mobile or desktop

			// HTML horizontal scroll for mobile
			if ( windowSize.w > 991 ) {
				// bind to vertical scroll for desktop

				let toolsAnimation = new GenericScrollHandler({
					"trigger" : $('.tools'),
					"subject" : $('.tools .items'),
					"animateOnce" : false, // animation only runs once
					"getDimensions" : (instance) => {
						instance.top = instance.trigger.position().top;

						instance.height = instance.trigger.height();
						instance.bottom = instance.top + instance.height;

						instance.start = instance.top; // top of module halfway up browser window
						instance.end = instance.bottom - (instance.wh); // bottom of module halfway up browser window

						instance.current = 0;

						// $('body').append($('<div></div>').addClass('mark').css({"top" : instance.top + "px", "background-color" : "green"}) );
						// $('body').append($('<div></div>').addClass('mark').css({"top" : instance.bottom + "px", "background-color" : "red"}) );

						// $('body').append($('<div></div>').addClass('mark').css({"top" : instance.start + "px", "background-color" : "green", "height" : "1px"}) );
						// $('body').append($('<div></div>').addClass('mark').css({"top" : instance.end + "px", "background-color" : "red", "height" : "1px"}) );
					},
					"monitor" : (instance,scrolltop) => {
						let percent = 0;

						if ( scrolltop < instance.top ) {
							// first frame
							instance.update(0.0);
						}

						if ( scrolltop >= instance.top && scrolltop <= (instance.top + toolTrack) ) {
							// last frame
							let percent = (scrolltop - instance.top) / toolTrack;
							instance.update(percent);
						}

						/*
						if ( scrolltop > instance.start && scrolltop < instance.end ) {

							let percent =  (scrolltop - instance.start ) / ( instance.end - instance.start );
							instance.state = true;
							instance.update(percent);	
						}
						*/

					},
					"update" : (instance,percent) => {

						if ( percent > 0 ) {
							let topmargin = (percent * toolTrack);
							$('.tools').css({"margin-top" : topmargin + "px"});
						}

						let progress = $('.toolsProgress li');
						let slider = $('.toolsWrapper2');
						let sliderItems = slider.find('.item');
						let slideItemCount = sliderItems.length;
						let step = (1.0 / slideItemCount);

						if (percent > 0.0 && percent < (step * 1.0)) {
							// 1
							instance.current = 0;
							slider.css({"left" : "0%"});
							progress.eq(0).addClass('active');
							progress.slice(1,5).removeClass('active');
						}

						if (percent > (step * 1.0) && percent < (step * 2.0) ) {
							// 2
							instance.current = 1;
							slider.css({"left" : "-100%"});
							progress.slice(0,2).addClass('active');
							progress.slice(2,5).removeClass('active');
						}

						if (percent > (step * 2.0) && percent < (step * 3.0)) {
							// 3
							instance.current = 2;
							slider.css({"left" : "-200%"});
							progress.slice(0,3).addClass('active');
							progress.slice(3,5).removeClass('active');
						}

						if (percent > (step * 3.0) && percent < (step * 4.0)) {
							// 4
							instance.current = 3;
							slider.css({"left" : "-300%"});
							progress.slice(0,4).addClass('active');
							progress.slice(4,5).removeClass('active');
						}

						if (percent > (step * 4.0) && percent < (step * 5.0)) {
							// 5
							instance.current = 4;
							slider.css({"left" : "-400%"});
							progress.slice(0,5).addClass('active');
							progress.slice(5,6).removeClass('active');
						}
						if (percent > (step * 5.0) && percent < 1.0) {
							// 6
							instance.current = 5;
							slider.css({"left" : "-500%"});
							progress.addClass('active');
						}

						if (percent > (step * 5.0)) {
							$('.toolsProgress').addClass('end');
						} else {
							$('.toolsProgress').removeClass('end');
						}

					}
				});

			}

		}

		// 'Who we are' page, home page 'Group' module - bind scroll to map animation
		if ( $('.lst-group').length > 0 ) {
		// set up mobile map
			let mtarget = $('.lst-group-m');
			let msource = mtarget.attr('data-source');
			let gmmAnimationFrames = lottie.loadAnimation({
				container: mtarget.get(0), 
				renderer: 'svg',
				loop: false,
				autoplay: false,
				path: msource 
			});

			// set up desktop map
			let dtarget = $('.lst-group-d');
			let dsource = dtarget.attr('data-source');
			let gmdAnimationFrames = lottie.loadAnimation({
				container: dtarget.get(0), 
				renderer: 'svg',
				loop: false,
				autoplay: false,
				path: dsource 
			});

			let gmdGraphAnimation = new GenericScrollHandler({
				"trigger" : $('.group'),
				"subject" : $('.group figure.mapgraph'),
				"animateOnce" : false, // animation only runs once
				"getDimensions" : (instance) => {

					instance.top = instance.trigger.position().top;

					if ($('.toolsWrapper1').length > 0) {
						instance.top = instance.top + toolTrack;
					}

					let headingHeight = instance.trigger.find('.topclip').height();
					let introHeight = instance.trigger.find('.msqinfo').height();
					instance.top = instance.top + ( headingHeight + introHeight);

					instance.height = instance.trigger.height();
					instance.bottom = instance.top + instance.height;

					instance.start = instance.top - (instance.wh / 2); // top of module halfway up browser window
					instance.end = instance.top;
					// instance.end = instance.bottom - (instance.wh / 2); // bottom of module halfway up browser window

					/*
					console.log("group start: %s end: %s", instance.start, instance.end);
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.top + "px", "background-color" : "black", "height" : "1px"}) );
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.bottom + "px", "background-color" : "black", "height" : "1px"}) );
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.start + "px", "background-color" : "green", "height" : "1px"}) );
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.end + "px", "background-color" : "red", "height" : "1px"}) );
					*/
				},
				"monitor" : (instance,scrolltop) => {
					let percent = 0;

					instance.state = false;

					// not reach CXM block yet
					if ( scrolltop < instance.start ) {
						// first frame
						instance.update(0.0);
					}

					// CXM block visible
					if ( scrolltop > instance.start && scrolltop < instance.end ) {

						let percent =  (scrolltop - instance.start ) / ( instance.end - instance.start );
						instance.state = true;
						instance.update(percent);	
					}

					// scrolled past CXM block
					if ( scrolltop > instance.end ) {
						// last frame
						instance.update(1.0);
					}
				},
				"update" : (instance,percent) => {

					/* this is the important part */
					gmdAnimationFrames.goToAndStop(percent * (gmdAnimationFrames.totalFrames - 1), true);			
					gmmAnimationFrames.goToAndStop(percent * (gmmAnimationFrames.totalFrames - 1), true);			
				}
			});
		}

	}
}

/*
let thisPageAnimation = new PageAnimations({});
thisPageAnimation.apply();
*/

/* ------------------------------------------------ */
// PeopleBios
/* ------------------------------------------------ */

class PeopleBios{
	constructor(parameters){

		this.bioOverlayModuleSelector = parameters.bioOverlayModuleSelector; 
		this.carouselSelector = parameters.carouselSelector;
		this.imageBinSelector = parameters.imageBinSelector;
		this.overlayFrameSelector = parameters.overlayFrameSelector;
		this.slickSliderSelector = parameters.slickSliderSelector;
		this.peopleItemsSelector = parameters.peopleItemsSelector;

		this.afterSlickSliderInit = parameters.afterSlickSliderInit;
		this.afterSlickSliderBreakpoint = parameters.afterSlickSliderBreakpoint;

		this.init();
	}

	init() {
		let that = this;

		this.people = [];
		this.peopleCount = 0;
		this.currentPerson = -1;
		this.peopleImages = [];
		this.bioOverlaysEnabled = false;
		this.peopleCarouselEnabled = false;
		this.slickSlider = null;
		this.overlayFrame = null

		this.bioOverlayModule = $(this.bioOverlayModuleSelector);
		if ( this.bioOverlayModule.length > 0 ) {
			this.bioOverlaysEnabled = true;
			this.overlayFrame = $(this.overlayFrameSelector);
			this.imageBin = $(this.imageBinSelector);
			this.ajaxLoad();

			this.overlayClose();
			this.overlayPaging();

			$(this.peopleItemsSelector).on('click',function(e){
				e.preventDefault();

				that.personClick($(this));
			});

			// stop social media link click propagation to bio overlay 
			$(this.peopleItemsSelector).find('.socialLinks a').on('click',function(e){
				e.stopPropagation();
			});

			// stop bio text link click propagation to bio overlay 
			$(this.peopleItemsSelector).find('.biov2 p a').on('click',function(e){
				e.stopPropagation();
			});
		}

		this.carousel = $(this.carouselSelector);
		if ( this.carousel.length > 0 ) {
			this.peopleCarouselEnabled = true;
			this.sspNode = $(this.slickSliderSelector);
			// this.slickSlideSetup();
		}
	}

	updateBioOverlay( person ) {
		/*
		person.person
			id 'person-XXX'
			forename
			surname
			bio
			role
			full
			portrait
			linkedin
			twitter
			instagram
			facebook
			email
			photo
		person.index
		*/

		let item = person.person;
		let overlay = $('#bioOverlay');

		$('#bioPhoto').attr({
			"src" : item.full,
			"alt" : item.forename + " " + item.surname + " - " + item.role
		}).on('load',function(e){
		});

		$('#bioName').text(item.forename + " " + item.surname);
		$('#bioRole').text(item.role);

		// $('#bioCopy').empty().append( item.bio );
		// need to clean up HTML entities in bio text
		// var tt = $('<div/>').html(item.bio).text();
		var tt = $('<div/>').html(item.bio);
		$('#bioCopy').empty().append( $('<p></p>').html(tt) );

		// show hide socail media links
		let mbSocial = overlay.find('.socialLinks');

		let hasLinkedin = ( item.linkedin !== '' );
		let hasFacebook = ( item.facebook !== '' );
		let hasEmail = ( item.email !== '' );
		let hasPhone = ( item.phone !== '' );

		// let hasSocialLinks = hasLinkedin || hasTwitter || hasInstagram || hasFacebook || hasEmail || hasPhone;
		let hasSocialLinks = hasLinkedin || hasEmail;
		if ( hasSocialLinks ) {

			let linkedinNode = mbSocial.find('.linkedin');
			if ( hasLinkedin ) {
				linkedinNode.attr({"href" : item.linkedin}).show();
			} else {
				linkedinNode.hide();
			}

			let emailNode = mbSocial.find('.email');
			if ( hasEmail ) {
				emailNode.attr({"href" : 'mailto:' + item.email}).show();
			} else {
				emailNode.hide();
			}

			mbSocial.find('.contactForename').text(item.forename)
			mbSocial.show();

		} else {
			mbSocial.hide();
		}

	}

	getPerson( id ) {
		let n = 0;
		while (n < this.peopleCount ) {
			if ( ('person-' + this.people[n].slug) === id ) {
				return {
					"person" : this.people[n],
					"index" : n
				};
			}
			n++;
		}
		return false;
	}

	getPersonBySlug( slug ) {
		let n = 0;
		while (n < this.peopleCount ) {
			if ( this.people[n].slug === slug ) {

				return {
					"person" : this.people[n],
					"index" : n
				};
			}
			n++;
		}
		return false;
	}

	ajaxLoad() {
		let that = this;

	    $.ajax({
	        url: localize_vars.ajaxurl,
	        type: "GET",
	        data: {
						"action" : "peoplepreload",
						"context" : $('section.people').attr('data-context')
	        },
	        dataType: 'json',
	        success: function (data) {

        		that.people = data.people;
        		that.peopleCount = that.people.length;

        		/// open overlay on People page if URL has name anchor
				if (window.location.href.indexOf('people') !== -1 ){
					// extract anchor -> person
					// #([a-z\-]+)

					const personFilter = /people\/#([a-z\-]+)/;
					let result = personFilter.exec(window.location.href);
					if(result) {
						// open bio overlay;
						let personToby = that.getPersonBySlug(result[1]); // needs to run AFTER the AJAX people preload

						// console.log("pagesize: %s", pageSize);
						switch(pageSize) {
							case 'desktop' : {
								that.updateBioOverlay(personToby);
								// $('html, body').stop().animate({"scrollTop" : $('body').offset().top }, 300);
								$('html, body').stop().animate({"scrollTop" : 0 }, 300);
								that.overlayFrame.addClass('open');
							} break;

							case 'mobile' : {
								// console.log('mobile');
								// find node
								let toby = $( '#person-' + personToby.person.slug );
								let dest = toby.position().top + 400;
								toby.toggleClass('open');

								// delay scroll to person until after image load/animation
								window.setTimeout(function(){
									// console.log("classes.js 2056 toby '%s' %s", personToby.person.slug, dest);
									// $('body').append( $('<div></div>').addClass('mark').css({"top" : dest + "px"}) );	
									window.scrollTo(0, dest );
								},125);
							} break;
						}
					}

				}
        		///

        		// build list of portratit / full images to queue for preload
				let peopleLoaderQueue = new QueueSingle({
					"settings" : {
						"delay" : 50 
					},
					"actions" : {
						// run when queue is started
						"queueStart" : function(queue){},

						// run when an item is started
						"itemStart" : function(queue,worker){
							// worker.item

								let newImage = $('<img />')
								.attr({
									"src" : worker.item.image,
									"data-person" : worker.item.person,
									"data-type" : worker.item.type
								})
								.on('load', function(e){
									window.setTimeout( () => {
										that.imageBin.append(newImage);
										queue.workerComplete();	
									}, queue.settings.delay );
								})

								
						},

						// run when an item finishes (image.load)
						"itemComplete" : function(queue,worker){
						},

						// run when all items have finished
						"queueComplete" : function(queue){
						}
					}
				});

				let n = 0;
				while ( n < that.people.length ) {
					let thisPerson = that.people[n];

					peopleLoaderQueue.addItem({
						"item" : {
							"person" : thisPerson.id, 
							"type" : "portrait",
							"image" : thisPerson.portrait, 
						}
					});

					peopleLoaderQueue.addItem({
						"item" : {
							"person" : thisPerson.id, 
							"type" : "full",
							"image" : thisPerson.full, 
						}
					});

					n++;
				}

				peopleLoaderQueue.startQueue();

			}
	    });
	}

	personClick(node) {
		let personNode = node;
		let subject = personNode.attr('id');

		let personXN = this.getPerson(subject);
		this.currentPerson = personXN.index;

		switch( pageSize ){
			case 'desktop' : {
				// populate overlay
				this.updateBioOverlay(personXN);
				$('html, body').stop().animate({"scrollTop" : $('body').offset().top }, 300);
				this.overlayFrame.addClass('open');

			} break;
			case 'mobile' : {
				personNode.toggleClass('open');
			} break;
		}
	}

	overlayClose() {
		let that = this;

		$('.closeBioOverlay').on('click',function(e){
			e.preventDefault();
			that.overlayFrame.removeClass('open');
		});

		this.overlayFrame.find('.white').on('click',function(e){
			that.overlayFrame.removeClass('open');
		});
	}

	smIconsAction(){
		let that = this;
		// this.peopleItemsSelector
	}

	overlayPaging() {
		let that = this;
		// handle desktop paging
		$('.paging li').on('click',function(e){
			e.preventDefault();

			let action = $(this).attr('class');
			let newPerson = -1;

			switch( action ) {
				case 'previous' : {
					newPerson = that.currentPerson - 1;
					if ( newPerson < 0 ) {
						newPerson = that.peopleCount - 1;
					}
				} break;
				case 'next' : {
					newPerson = that.currentPerson + 1;
					if ( newPerson === that.peopleCount ) {
						newPerson = 0;
					}

				} break;
			}

			that.currentPerson = newPerson;
			that.updateBioOverlay( that.getPersonBySlug( that.people[that.currentPerson].slug ) );
		});

	}

	slickSlideBreakpointEvent() {
		let that = this;
		this.sspNode.on('breakpoint', function(event, slick, direction){
			// --------------------------------------------------------------------------------------------------------
			// start of slickslide 'breakpoint' event
			// --------------------------------------------------------------------------------------------------------

			// attach behaviours to items in carousel and apply animation
			that.afterSlickSliderBreakpoint();

			// --------------------------------------------------------------------------------------------------------
			// end of slickslide 'breakpoint' event
			// --------------------------------------------------------------------------------------------------------
		});
	}

	slickSlideInitEvent() {
		let that = this;
		this.sspNode.on('init', function(event, slick, direction){

			// --------------------------------------------------------------------------------------------------------
			// start of slickslide 'init' event
			// --------------------------------------------------------------------------------------------------------

			// stop transition on child nodes bubbling up to .fwop
			$('.fwop').children().each(function(i){
				$(this).get(0).addEventListener('transitionend', (e) => {
					e.stopPropagation();
				});
			})

			// fwop transition has ended, the slickslider carousel is now visible
			$('.fwop').get(0).addEventListener('transitionend', (e) => {
				let peopleAnimation = new GenericScrollHandler({
					"trigger" : $('.peopleSlickSlider'),
					"subject" : $('.peopleSlickSlider'),
					"animateOnce" : true, // animation only runs once
					"getDimensions" : (instance) => {
						instance.top = instance.trigger.position().top;
						instance.height = instance.trigger.height();
						instance.bottom = instance.top + instance.height;

						instance.start = instance.top - (instance.wh / 2); // top of 'people' module halfway up browser window
						instance.end = instance.bottom - (instance.wh / 2); // bottom of 'people' module halfway up browser window
					},
					"monitor" : (instance,scrolltop) => {
						if ( scrolltop > instance.start && scrolltop < instance.end ) {
							instance.update(1.0);	
						} else {
							instance.update(0.0);
						}
						
					},
					"update" : (instance,percent) => {
						if ( instance,percent > 0.0) {
							// $('.people .slick-active .item').addClass('ready');
							$('.people .item').addClass('ready');	
						}
					}
				});

				// attach behaviours to items in carousel and apply animation
				// thisPageAnimation.apply();
			});

			$('.fwop').addClass('ready');

			that.afterSlickSliderInit();

			// --------------------------------------------------------------------------------------------------------
			// end of slickslide 'init' event
			// --------------------------------------------------------------------------------------------------------

		});
	}

	slickSlideSetup() {
		// this.slickSlideInitEvent();

		// double row, 4/3/2/1 column carousel
		// clicks through to Poeple page /people/ and opens relevant bio overlay
		this.sspNode.slick({
			"dots" : false,
			"arrows" : true,
			"infinite" : true,
			"slidesToShow" : 4,
			"slidesToScroll" : 4,
			"rows" : 2,
			 "responsive": [
				{
					"breakpoint" : 1400,
					"settings" : {
						"slidesToShow" : 4,
						"slidesToScroll" : 4,
						"rows" : 2
					}
				},
				{
					"breakpoint" : 1200,
					"settings" : {
						"slidesToShow" : 3,
						"slidesToScroll" : 3,
						"rows" : 2
					}
				},
				{
					"breakpoint" : 992,
					"settings" : {
						"slidesToShow" : 2,
						"slidesToScroll" : 2,
						"rows" : 2
					}
				},
				{
					"breakpoint" : 768,
					"settings" : {
						"dots" : false,
						"arrows" : true,
						"slidesToShow" : 1,
						"slidesToScroll" : 1,
						"rows" : 2
					}
				}
			 ]
		});
	}
}

/*
*/