/* ------------------------------------------------ */
// ScrollMonitor
/* ------------------------------------------------ */

function ScrollMonitor(parameters) {
	this.parameters = parameters;

	this.dotsSelector = parameters.dotsSelector;
	this.moduleClass = parameters.moduleClass;

	this.init();
}

ScrollMonitor.prototype = {
	"constructor" : ScrollMonitor,
	"template" : function () {let that = this; },
	
	"init" : function () {
		let that = this;

		this.dots = $(this.dotsSelector);
		this.modules = $(this.moduleClass);
		this.moduleCount = this.modules.length;
		this.currentModule = 0;
		this.currentModuleType ='';
		that.currentScrollTop = 0;
		this.moduleIndex = [];
		this.bgpanels = [];

		this.singlePanelbg = null;
		this.singlePanelbgIndexed = false;

		this.indexModules();
		this.bindScroll();
	},
	"indexModules" : function () {
		let that = this;

		// class="" (text|picture|work|capabilities)
		let moduleTypesRegex = RegExp('(videoModule|everyones-a-switcher|text|videogrid|work|journey)');

		let rules = null;
		let rulesOffset = 0;
		let rulesTop = 0;

		// get scroll offset of each module
		this.modules.each((i,el) => {
			let module = $(el);
			let rt = moduleTypesRegex.exec(module.attr('class'));
			let moduleType = (rt) ? rt[1] : "";
			let moduleID = module.attr('id');
			let moduleOffset = module.position();
			let height = module.height();
			let top = moduleOffset.top;
			let bottom = top + height;

			/* fade in work module
			if( moduleType === 'work' ) {
				that.workNode = $(this);
				that.workTrigger = {
					"top" : moduleOffset.top - (windowSize.h / 2)
				};
			}
			*/

			if( moduleType === 'tailPic' ) {
				height = windowSize.w * 0.469; // 46.9% aspect ratio
				bottom = top + height;
			}

			if( moduleType === 'videoModule' ) {
				that.videoBottom = bottom;
			}

			let moduleData = {
				"index" : i,
				"id" : moduleID,
				"type" : moduleType,
				"top" : top,
				"bottom" : bottom,
				"height" : height
			}

			that.moduleIndex.push(moduleData);
		});


		this.moduleCount = this.moduleIndex.length;
		this.lastModule = this.moduleCount - 1;
		this.maxHeight = this.moduleIndex[this.lastModule].top + this.moduleIndex[this.lastModule].height;

		// add dummy module entry to mark end of page
		let footerBox = $('footer').eq(0);
		let footerBoxPosition = footerBox.position();

		that.moduleIndex.push({
			"index" : this.moduleCount,
			"id" : "footer",
			"type" : "footer",
			"top" : footerBoxPosition.top,
			"bottom" : footerBoxPosition.top + footerBox.height(),
			"height" : footerBox.height()
		});

		this.currentModule = 0;
		this.currentModuleType = this.moduleIndex[this.currentModule].type
		this.currentModuleNode = $('#' + this.moduleIndex[this.currentModule].id);

		/* find modules visible when page loads */
		let nModule = null;
		let currentScrollTop = $(window).scrollTop();
		let visibleItems = [];
		let notVisibleItems = [];
		for(var n = 0; n < this.moduleIndex.length; n++) {
			nModule = this.moduleIndex[n];

			// find visible items
			if( ( nModule.top < (currentScrollTop + windowSize.h) ) && ( nModule.bottom > currentScrollTop ) ) {
				this.dots.eq(n).addClass('on');
				$('#' + nModule.id).addClass('active');
			}
		}
		/**/
	},
	"bindScroll" : function () {
		let that = this;

		$(window).scroll(function(){
			// let throttle = window.setTimeout(function(){

				that.scrollHandler();
			// }, 10);
		});
	},
	"scrollHandler" : function () {
		let that = this;

		let currentScrollTop = $(window).scrollTop();

		let nModule = {};
		let visibleItems = [];
		let notVisibleItems = [];

		for(var n = 0; n < this.moduleIndex.length; n++) {
			nModule = this.moduleIndex[n];

			// find visible items
			if( ( nModule.top < (currentScrollTop + windowSize.h) ) && ( nModule.bottom > currentScrollTop ) ) {
				this.dots.eq(n).addClass('on');
				visibleItems.push({
					"index" : n,
					"type" : nModule.type,
					"id" : nModule.id,
					"top" : nModule.top,
					"top2" : (nModule.top - windowSize.h),
					"bottom" : nModule.bottom ,
					"range" : nModule.bottom - (nModule.top - windowSize.h),
					"hasVideoBackground" : nModule.hasPanelVideo,
					"videoBackgroundIndex" : ((nModule.hasPanelVideo) ? nModule.panelVideo : -1 )
				});
			} else {
				// handle items moving out of visibility

				notVisibleItems.push({
					"id" : nModule.id
				});

				this.dots.eq(n).removeClass('on');
			}
		}

		window.requestAnimationFrame( function(x){

			if (currentScrollTop > that.videoBottom) {
				$('.logoAnimation').addClass('go');
			} else {
				$('.logoAnimation').removeClass('go');
			}

			notVisibleItems.map(function(item){
				$( '#' + item.id ).removeClass('active ta hold');

				//check for video backgrouns moving out of view
			},that);		

			visibleItems.map(function(item){
				let itemNode = $( '#' + item.id );

				let percent = (currentScrollTop - item.top2)  / item.range;

				switch(item.type){
					case "text" : {
						itemNode.addClass('active');						

						if( ( item.top <= currentScrollTop ) ){
							itemNode.addClass('ta');
						}

						// detect when current item begins to scroll off of top of browser window
						if( (item.top + windowSize.h) <= currentScrollTop ){
							itemNode.removeClass('ta');
						}

					} break;

					case "videogrid" : {
						itemNode.addClass('active');						

						/*
						if( ( item.top <= currentScrollTop ) ){
							itemNode.addClass('ta');
						}

						if( (item.top + windowSize.h) <= currentScrollTop ){
							itemNode.removeClass('ta');
						}
						*/
					} break;

					case "everyones-a-switcher" : {
						itemNode.addClass('active');						
					} break;
					case "journey" : {
						itemNode.addClass('active');						
					} break;

					case "work" : {
						if( ( item.top < currentScrollTop ) && !itemNode.hasClass('active') ){
							itemNode.addClass('active');						
						}
					} break;
					default : {} break;
				}
			},that);
		});
	}

	/* --------------------------------------------------------------------------- */

}


// navigation dots plus panel visibility animation
let homeScrollMonitor = new ScrollMonitor({
	"dotsSelector" : "ul.position li",
	"moduleClass" : ".module"
});
