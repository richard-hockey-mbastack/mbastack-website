/* ------------------------------------------------ */
// HomeVideo
/* ------------------------------------------------ */

function HomeVideo(parameters) {
	this.parameters = parameters;

	this.destination = parameters.destination;
	this.sources = parameters.sources;
	this.delay = parameters.delay;
	this.videotag = parameters.videotag;

	this.init();
}

HomeVideo.prototype = {
	"constructor" : HomeVideo,
	"template" : function () {var that = this; },
	
	"init" : function () {
		var that = this;

		// first panel video
		// let newVideoTag = $('<video id="homevideo" preload="metadata" muted nocontrols playsinline data-xephon="8817" poster="dist/img/stack-poster.png"></video>');
		this.newVideoTag = $(this.videotag);

		// dist/img/stack-poster.png
		// let posterImg = $('<img alt="STACK" src="' + this.sources.poster + '" class="hpimg"></img>');

		// mp4 appears to be supported by all
		// safari only supports mp4
		// video corruption in possible windows firefox due to video drivers
		this.newVideoTag.append( this.buildsource("video/mp4", this.sources.mp4) );
		this.newVideoTag.append( this.buildsource("video/webm", this.sources.webm) );
		this.newVideoTag.append( this.buildsource("video/ogg", this.sources.ogv) );

		/* Safari does not emit the 'canplay' event
		newVideoTag.on('canplay',function(){ console.log('canplay'); });
		*/

		/*
		newVideoTag.on('readystatechange',function(){
			var video = $(this).get(0), readyInfo = video.readyState;
			console.log("readystatechange - readystate: %s", readyInfo);
		});
		*/

		// autoplay
		this.autoplay();

		// loop
		this.loop();

		$(this.destination).prepend( this.newVideoTag );
	},
	"buildsource" : function (type,path) {
		var that = this;
		let newSource = $('<source></source>').attr({
			"type" : type,
			"src" : path
		});
		return newSource;
	},
	"autoplay" : function () {
		var that = this;

		this.newVideoTag.on('loadedmetadata',function(){
			var video = $(this).get(0), readyInfo = video.readyState;
			
			//if ( readyInfo > 3) {
				// placeholder 'STACK' fade from white to blue, 2 second duration, CSS animation 'whitetoblue' 
				$('#module00').addClass('ready');

				// start video after initinal colour change
				// video starts at same time as placeholder opacity fade from 1 to 0, 1 second duration, CSS animation 'fadeout'
				window.setTimeout(function() {
					video.play();
				}, that.delay)
			//}
		});
	},
	"loop" : function () {
		var that = this;

		this.newVideoTag.on('ended',function(){
			console.log('video ended');
			$(this).get(0).currentTime = 0;
			$(this).get(0).play();
		});
	}

	/* --------------------------------------------------------------------------- */

}

window.HomeVideo = HomeVideo;
export default HomeVideo;


// home page first panel 'stack' video
let thisHomeVideo = new HomeVideo({
	"destination" : "#hv",
	"videotag" : '<video id="homevideo" preload="metadata" muted nocontrols playsinline loop data-xephon="8817"></video>',
	"sources" : {
		"poster" : "/img/stack-poster.png",
		"mp4" : "/video/home/stack_web_intro-1kbps.mp4",
		"webm" : "",
		"ogv" : ""
	},
	"delay" : 2000
});
