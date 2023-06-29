/* default */

// localize_vars passed from function.php -> mbastack_enqueue_scripts

window.windowSize = 0;

let toolTrack = 2500; // scroll range of 'who we are' tools panel
let csJumpOverride = false;

/* ---------------------------------------------------------------- */

// footer google maps instance

var offices = [];
// run once google maps library has loaded
function initmap(){

	// footer office map
	// read office location from data attributes in page
	
	// let officeMap = $('#mapTarget');
	let officeMap = $('#contactMap0');

	if( officeMap.length > 0 ) {

			offices.push({
				"lat" : officeMap.attr('data-lat'),
				"long" : officeMap.attr('data-long'),
				"zoom" : parseFloat(officeMap.attr('data-zoom'))
			});
			// console.log(offices);

			let thisOffice = offices[0];
			let officeLocation = new google.maps.LatLng(thisOffice.lat,thisOffice.long);
			let mapSettings = {
		        zoom: thisOffice.zoom,
		        center: officeLocation,
		        mapTypeId: google.maps.MapTypeId.ROADMAP,
		        zoomControl: !1,
		        zoomControlOptions: {
		            style: google.maps.ZoomControlStyle.BIG,
		            position: google.maps.ControlPosition.TOP_LEFT
		        },
		        mapTypeControl: !1,
		        mapTypeControlOptions: {
		            style: google.maps.ZoomControlStyle.SMALL,
		            position: google.maps.ControlPosition.TOP_RIGHT
		        },
		        minZoom: 2,
		        maxZoom: 20,
		        scaleControl: !1,
		        scrollwheel: false,
		        navigationControl: !1,
		        streetViewControl: !1,
		        panControl: !1

			};
			let mapItem = new google.maps.Map(document.getElementById("contactMap0"),mapSettings);

			// set map marker once tiles have loaded
			mapItem.addListener('tilesloaded', function(me) {

		    	// define custom icon
				let mbastackIcon = {
					"url" : localize_vars.url + "/assets/img/map-pin.png",
					"anchor" : new google.maps.Point(12,36),
					"size" : new google.maps.Size(24,36),
					"scaledSize" : new google.maps.Size(24,36)
				};

				let newMarker = new google.maps.Marker({
			      "position" : officeLocation,
			      "icon" : mbastackIcon,
			      // "animation" : google.maps.Animation.DROP
			      "map" : mapItem,
			    });

				/* */
			    newMarker.addListener('click', function(poiMarker) {
			    	// window.open('https://www.google.co.uk/maps/place/Tottenham+Court+Rd,+Fitzrovia,+London+W1T+4TJ/@51.5220482,-0.1381659,17z/data=!3m1!4b1!4m5!3m4!1s0x48761b293a4be25f:0xa8c2275ab2d6d797!8m2!3d51.5219813!4d-0.1360347');
			    });
			    /**/
			});

	}
	
}


/* ---------------------------------------------------------------- */

/*
	-> HomeVideo -> source/js/classes.js -> assets/js/classes.min.js
*/

/* --------------------------------------------------------------------------- */


// https://snook.ca/archives/javascript/normalize-bootstrap-carousel-heights
// iterate through all carousels in page, then over all items in a given carousel
// find the max height of an item in a given carousel
// set the height of all items in the carousel to that height

function normalizeSlideHeights() {
    $('.carousel').each(function(){
      var items = $('.carousel-item', this);
      // reset the height
      items.css('min-height', 0);
      // set the height
      var maxHeight = Math.max.apply(null, 
          items.map(function(){
              return $(this).outerHeight()}).get() );
      items.css('min-height', maxHeight + 'px');
    })
}

$(window).on(
    'load resize orientationchange', 
    normalizeSlideHeights);


/* ---------------------------------------------------------------- */


function windowDimensions() {
	windowSize = {
		"w" : $(window).width(),
		"h" : $(window).height(),
		"a" : $(window).width() / $(window).height()
	};

	if (windowSize.a > 1) {
		landscapeAspect = true;

		if (landscapeAspect && windowSize.h < 500) {
			shortLandscape = true;
		} else {
			shortLandscape = false;
		}
	} else {
		landscapeAspect = false;
	}

	if(landscapeAspect && !shortLandscape) {
		$('html').addClass('landscapeAspectRatio');
	}
	if(landscapeAspect && shortLandscape) {
		$('html').addClass('shortLandscape');
	}
	if(!landscapeAspect) {
		$('html').addClass('portraitAspectRatio');
	}
}


/* ---------------------------------------------------------------- */

const winheight = $(window).height();
const threequarterheight = Math.floor( winheight * 0.75);
const halfheight = Math.floor( winheight * 0.5);
const quarterheight = Math.floor( winheight * 0.25);

let pageSize = null;
let thisPageAnimation = null;

$(document).ready(function () {


	let isIOS = (/iPad|iPhone|iPod/.test(navigator.platform) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)) && !window.MSStream;
	// console.log(isIOS);
	if (isIOS) {
		$('html').addClass('is-ios');
	}

	if( $('.carousel').length > 0) {
		normalizeSlideHeights();
	}

	windowDimensions();

	pageSize = ( windowSize.w > 767 ) ? 'desktop' : 'mobile';

	$(window).on('resize',() => {
		windowDimensions();
		pageSize = ( windowSize.w > 767 ) ? 'desktop' : 'mobile';
	});

	/* ---------------------------------------------------------------- */

	/* dpendent on the menu item IDs - if the menu layout changes this code will need o be updated */

	let navHeader = $('header.navheader');
	let logoDesktop = $('.navbar-brand-desktop');
	let logoMobile = $('.navbar-brand-menu');

	$('.navbar-toggler').on('click',function(e){
		e.preventDefault();
		
		logoDesktop.toggleClass('show');
		logoMobile.toggleClass('show');
		navHeader.toggleClass('fixed');
	});

	// set news menu item '#menu-item-37' state to 'current-page-ancestor' when post is open
	if ($('section.singlePost').length > 0 ) {
		$('#menu-item-37').addClass('current-page-ancestor');
	}

	// set work menu item '#menu-item-38' state to 'current-page-ancestor' when casestudy is open
	if ($('section.case-study-banner').length > 0 ) {
		$('#menu-item-38').addClass('current-page-ancestor');
	}

	// mobile navigation fixed toggle button
	function fixNavBar(){
		let scrollState = $(window).scrollTop();
		let navBar = $('.navbar');
		
		// fix navbar once user scrolls down
		/*
		let newState = ( scrollState > 0 );
		// detect change of state
		if ( newState !== navTogglerStateFixed ) {
			if( newState ) {
				// apply fix
				navBar.addClass('fixed');
			} else {
				// remove fix
				navBar.removeClass('fixed');				
			}
			navTogglerStateFixed = newState;			
		}
		*/

		// slide navbar in from top of screen when user scrolls up
		if ( scrollState > 0) {
			navBar.addClass('fixedHide');	
		} else {
			navBar.removeClass('fixedHide');	
		}
		// check for scroll up
		if ( scrollState < p) {
			navBar.addClass('fixedShow');	
		} else {
			navBar.removeClass('fixedShow');
		}
		p = scrollState;
	}

	let navTogglerStateFixed = false;
	let p = 0;

	let navfix = $(window).scroll(() => {
		fixNavBar();
	});
	fixNavBar();

	/* ---------------------------------------------------------------- */

	/* ---------------------------------------------------------------- */
	/* */

	// page animations
	// who we are: + CXM + tools + group
	// values: + trinity
	// gsap.js


	/*
	BindVideos ->  -> source/js/classes.js -> assets/js/classes.min.js
	*/
	// bind video to pages without carousels
	let bindVideos = new BindVideos({});

	/*
	-> PageAnimations -> source/js/classes.js -> assets/js/classes.min.js
	*/

	thisPageAnimation = new PageAnimations({});

	// apply animations to all pages except home page
	// home page animations bpund to slick slider init events	
	if ( $('main.homePage').length === 0 && $('.case-study-media_carousel').length === 0) {
		thisPageAnimation.apply();	
	}

	// scroll down button
	if ( $('a.scrolldown').length > 0 ) {
		$('a.scrolldown').on('click',function(e){
			e.preventDefault();

			let thisSCL = $(this);
			let thisSCLAction = thisSCL.attr('data-target');
			let thisSCLContainer = thisSCL.attr('data-container');
			let parentModule = thisSCL.parents(thisSCLContainer).eq(0);

			switch( thisSCLAction ){

				// go to next module
				// data-target="nextmodule"
				case 'nextmodule' : {
					let ald = parentModule.nextAll('section.module').eq(0);
					let destTop = ald.position().top;
					window.scrollTo(0, destTop );	
				} break;

				// other actions to go here
			}
		});
	}

	// case study horizontal scroller
	
	if ( $('.csfwop').length > 0 ) {
		let caseStudyWindow = $('.csfwop');
		let caseStudyTrack = $('.caseStudyCarousel');
		let caseStudyCount = $('.caseStudy').length;
		let caseStudyStep = Math.floor(caseStudyTrack.width() / caseStudyCount);
		let currentStep = 0;
		csJumpOverride = false;

		let lastOffset = caseStudyWindow.width() - caseStudyStep;

		$(window).on('resize',() => {
			caseStudyStep = Math.floor(caseStudyTrack.width() / caseStudyCount);
		});

		$('.csfwop').on('scroll',(e) => {
			let x = e.target;
			let hScroll = $(x).scrollLeft();

			if ( csJumpOverride && hScroll === (currentStep * caseStudyStep) ) {
				// ignore current scroll position until it reaches the required element
				csJumpOverride = false;
			} else {

				//
				let n = 0;
				while ( n < caseStudyCount ) {
					if ( hScroll >= (n * caseStudyStep) && hScroll < ((n + 1) * caseStudyStep) ) {
						currentStep = n;	
					}

					if ( hScroll >= ( ((caseStudyCount - 1) * caseStudyStep) - lastOffset)) {
						currentStep = n;	
					}
					n++;
				}
				//
				
			}
		})

		// go to prev panel, loop back to last item
		$('div.prevItem').on('click', (e) => {

			currentStep--;

			if ( currentStep === -1) {
				currentStep = (caseStudyCount - 1.0);
			}

			csJumpOverride = true;

			$('.csfwop').animate({
				"scrollLeft" : (currentStep * caseStudyStep)
			},500);

		});

		// go to next panel, loop back to start
		$('div.nextItem').on('click', (e) => {

			currentStep++;

			if ( currentStep === caseStudyCount) {
				currentStep = 0;
			}

			csJumpOverride = true;

			$('.csfwop').animate({
				"scrollLeft" : (currentStep * caseStudyStep)
			},500);

		});

		if ( pageSize === 'desktop' ) {
			// left/right drag - desktop only
			// event order: mpusedown,click

			// let catcher = caseStudyTrack.find('a.csLink').eq(0);
			let starter = caseStudyTrack.find('a.csLink');
			let catcher = caseStudyTrack.find('.dragTrack').eq(0);
			let mouseDrag = false, mouseStartX = 0, mouseStartY = 0, currentScroll = 0, _starter = null, _starterX = 0;

			let upHandler = function(e){
				currentScroll = $('.csfwop').scrollLeft();

				catcher.off('mousemove');
				catcher.off('mouseup');
				catcher.off('mouseout');
				mouseDrag = false;
				catcher.hide();

				if ( (_starterX + 10 > currentScroll) && (_starterX - 10 < currentScroll) ) {
					window.location.href = _starter.attr('href');
				}
			};

			let outHandler = upHandler;

			let moveHandler = function(e){
				let dx = -(e.pageX - mouseStartX);
				if( mouseDrag ) {
					$('.csfwop').scrollLeft( currentScroll + dx );
				}
				
			};

			let downHandler = function(e){
				// add slight delay before context switches from case study link to dragTarget
				// allow the case stud to be clickable without 
				// window.setTimeout(function(){
					mouseDrag = true;
					catcher.show();
					mouseStartX = e.pageX;
					currentScroll = $('.csfwop').scrollLeft();
					_starter = $(e.target);				
					_starterX = currentScroll;

					catcher.on('mousemove', moveHandler );
					catcher.on('mouseup', upHandler );
					catcher.on('mouseout', outHandler );
				// },100);
			};

			starter.on('mousedown', downHandler );

			// scroll to relevant case study panel when user focusses on it using Tab key
			let focusHandler = function(e){
				let _caseStudy = $(e.target).parents('.caseStudy');
				let _index = $('.caseStudy').index( _caseStudy );
				currentStep = _index;
				$('.csfwop').animate({
					"scrollLeft" : (currentStep * caseStudyStep)
				},500);
			};
			// starter.on('focus', focusHandler);

			/*
			starter.on('click', (e) => {
				console.log('caseStudy csLink click');
				e.preventDefault();
			} );
			*/
		}
	}


	/* ---------------------------------------------------------------- */

	/* home page */
	// has at least three carousels - need to apply the animations AFTER all of the carousels have initialised
	// potentially -> banner
	// case studies
	// clients (using bootsrtap carousel currently, may need to transtion to slickslider)
	// people

	// 'Group' panel on home page- animation is handled seperately from GSAP page animations

	// replaced suboptimal bootstrap carousel with slickslider
	/* not currently applied but will need to applied in future
  $('#hbc01').slick({
  	"autoplaySpeed" : 5000,
  	"autoplay" : true,
  	"dots" : true,
  	"arrows" : true,
  	"appendArrows" : ".hbcontrols",
  	"appendDots" :  ".hbcontrols",
		"responsive": [
			{
				"breakpoint" : 1200,
				"settings" : {
			  	"dots" : false
				}
			}
		]
  });
	*/

	// queue for homepage carousels
	// queue each carousel and when all have initialised set up page animations and video elements
	// also set up slider items which have their own behaviours:
	// people -> click to people page, case studies -> video play/hover
	let testQueue = new Queue({
		"itemDelay" : 250,
		"workerLimit" : 8,
		"queueStartAction" : function(queue){
		},
		"queueCompleteAction" : function(queue){
			console.log("**");
			console.log("---- queueCompleteAction START");
			console.log("**");

			// needs a small delay (> 25ms) before the lottiescroll 'Group' map is correctly indexed
			window.setTimeout(function(){
				thisPageAnimation.apply();	
				// let bindVideos = new BindVideos({});
			}, 100);


			console.log("**");
			console.log("---- queueCompleteAction END");
			console.log("**");
		},
		"workerStartAction" : function(queue,worker){
			// console.log("-- worker start name: %s", worker.properties.name);

			worker.properties.slider.configure(queue, worker);
		},
		"workerCompleteAction" : function(worker){
			// console.log("--- worker complete name: %s", worker.properties.name);
		}
	});

	// banner home page carousel

	// case studies home page carousel + case study page 'more case studies carousel'
	/*
	if ( $('.caseStudyCarousel').length > 0 ) {
		testQueue.addItem({
			"name" : "case study carousel",
			"slider" : {
				"selector" : $('.caseStudyCarousel'),
				"configure" : function(queue,worker){
					// console.log("Alpha %s", worker.properties.name);

					let slider = worker.properties.slider.selector;

				  // slider.on('breakpoint', function(event, slick, direction){});

				  slider.on('init', function(event, slick, direction){
						$('.csfwop').children().each(function(i){
							$(this).get(0).addEventListener('transitionend', (e) => {
								e.stopPropagation();
							});
						})

						// fwop transition has ended, the slickslider carousel is now visible
						$('.csfwop').get(0).addEventListener('transitionend', (e) => {
							worker.complete();
						});

						$('.csfwop').addClass('ready');
				  });

			  	slider.slick({
				  	"autoplaySpeed" : 5000,
				  	"autoplay" : false,
				  	"dots" : true,
				  	"arrows" : true,
						"responsive": [
							{
								"breakpoint" : 992,
								"settings" : {
							  	"dots" : true
								}
							}
						]
				  });

				}
			}
		});
	}
	*/

	// clients home page carousel
	if ( $('.latestClients').length > 0 ) {

		let carouselNode = $('#homeClientsCarousel');

		// https://getbootstrap.com/docs/4.2/components/carousel/#via-javascript
 	 	if ( windowSize.w > 991 ) {
 	 		// desktop
 	 		// apply automatic animation on 4000 millisecond interval
			carouselNode.carousel({
			  "interval" : 4000,
			  "ride" : "carousel", // automatically start animating on load
			  "pause" : "hover" // pause animation on hover/touchstart
			})
 	 	} else {
 	 		// mobile
 	 		// no automatic animation
			carouselNode.carousel({
			  "interval" : false, // disable automatic animation entirely
			  "ride" : false,
			  "pause" : "hover" // pause animation on hover/touchstart
			})
 	 	}

 	 	// update properties of bootstrap carousel after it has been initialised and configured
 	 	// https://stackoverflow.com/questions/30271054/how-to-change-interval-of-a-bootstrap-carousel-once-it-has-been-initialised

		$(window).on('resize',() => {
			windowDimensions();

	 	 	// get current carousel propeties
	 		let carouselOptions = carouselNode.data()['bs.carousel']['_config'];

	 	 	if ( windowSize.w > 991 ) {
	 	 		// desktop
	 	 		// apply automatic animation on 4000 millisecond interval

	 	 		// update 'interval' and 'ride' properties of carousel 
	 	 		// interval : 4000 automatic animation, 4000 millisecond i nterval
	 	 		// ride : carousel automatically start animating on load
	 	 		carouselOptions.interval = 4000;
	 	 		carouselOptions.ride = 'carousel';

	 	 	} else {
	 	 		// mobile
	 	 		// no automatic animation

	 	 		// update 'interval' and 'ride' properties of carousel 
	 	 		// interval : false // no automatic animation
	 	 		// ride : false 
	 	 		carouselOptions.interval = false;
	 	 		carouselOptions.ride = false;
	 	 	}

	 	 	// apply new settinsg to carousel
	 	 	carouselNode.data({"_config" : carouselOptions});

	 	 	carouselNode.carousel('pause');
	 	 	carouselNode.carousel('cycle');

		});

 	 	// devices with touch events - add left/right swipe
		$('.latestClients .carousel').on('touchstart', function(event){
		    const xClick = event.originalEvent.touches[0].pageX;
		    $(this).one('touchmove', function(event){
		        const xMove = event.originalEvent.touches[0].pageX;
		        const sensitivityInPx = 5;
		        if( Math.floor(xClick - xMove) > sensitivityInPx ){
		            $(this).carousel('next');
		        }
		        else if( Math.floor(xClick - xMove) < -sensitivityInPx ){
		            $(this).carousel('prev');
		        }
		    });
		    $(this).on('touchend', function(){
		        $(this).off('touchmove');
		    });
		});		
	}


	// people home page carousel
	if ( $('.peopleSlickSlider').length > 0 ) {
		testQueue.addItem({
			"name" : "people carousel",
			"slider" : {
				"selector" : $('.ssp'),
				"configure" : function(queue,worker){

					let slider = worker.properties.slider.selector;

				  slider.on('init', function(event, slick, direction){
				  	worker.complete();
				  });

				  // slider.on('breakpoint', function(event, slick, direction){});

				  slider.on('init', function(event, slick, direction){
				  	// console.log("slickslider [%s] init event", worker.properties.name)

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

							worker.complete();

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
						});

						$('.fwop').addClass('ready');

						// --------------------------------------------------------------------------------------------------------
						// end of slickslide 'init' event
						// --------------------------------------------------------------------------------------------------------

				  });

			  	slider.slick({
						"dots" : false,
						"arrows" : true,
						"infinite" : false,
						// "slidesToShow" : 4,
						// "slidesToScroll" : 4,
						"slidesPerRow" : 4,
						"rows" : 2,
						 "responsive": [
							{
								"breakpoint" : 1400,
								"settings" : {
								// "slidesToShow" : 4,
								// "slidesToScroll" : 4,
								"slidesPerRow" : 4,
									"rows" : 2
								}
							},
							{
								"breakpoint" : 1200,
								"settings" : {
								// "slidesToShow" : 3,
								// "slidesToScroll" : 3,
								"infinite" : false,										
								"slidesPerRow" : 3,
									"rows" : 2
								}
							},
							{
								"breakpoint" : 992,
								"settings" : {
									"infinite" : false,										
									// "slidesToShow" : 2,
									// "slidesToScroll" : 2,
									"slidesPerRow" : 2,
									"rows" : 2
								}
							},
							{
								"breakpoint" : 768,
								"settings" : {
									"dots" : false,
									"arrows" : true,
									"infinite" : false,
									// "slidesToShow" : 1,
									// "slidesToScroll" : 1,
									"slidesPerRow" : 1,
									"rows" : 2
								}
							}
						 ]
				  });

			  	slider.slick('refresh');
				}
			}
		})
  }

  // case study page media carousel
  if ( $('.case-study-media_carousel').length > 0 ) {
		testQueue.addItem({
			"name" : "case study page media carousel",
			"slider" : {
				"selector" : $('.mcSS'),
				"configure" : function(queue,worker){

					let slider = worker.properties.slider.selector;

				  slider.on('init', function(event, slick, direction){
						$('.mcfwop').children().each(function(i){
							$(this).get(0).addEventListener('transitionend', (e) => {
								e.stopPropagation();
							});
						})

						// fwop transition has ended, the slickslider carousel is now visible
						$('.mcfwop').get(0).addEventListener('transitionend', (e) => {
							worker.complete();
						});

						$('.mcfwop').addClass('ready');				  	
				  });

				  // slider.on('breakpoint', function(event, slick, direction){});

			  	slider.slick({
				  	"autoplaySpeed" : 5000,
				  	"autoplay" : false,
				  	"dots" : false,
				  	"arrows" : false,
						"responsive": [
							{
								"breakpoint" : 992,
								"settings" : {
							  	"dots" : false
								}
							}
						]
				  });

				}
			}
		});

  }

  // case study page image carousel
  if ( $('.case-study-image_carousel').length > 0 ) {
		testQueue.addItem({
			"name" : "case study page media carousel",
			"slider" : {
				"selector" : $('.imageCarouselSlickSlider'),
				"configure" : function(queue,worker){

					let slider = worker.properties.slider.selector;

				  slider.on('init', function(event, slick, direction){
						$('.icfwop').children().each(function(i){
							$(this).get(0).addEventListener('transitionend', (e) => {
								e.stopPropagation();
							});
						})

						// fwop transition has ended, the slickslider carousel is now visible
						$('.icfwop').get(0).addEventListener('transitionend', (e) => {
							worker.complete();
						});

						$('.icfwop').addClass('ready');				  	
				  });

				  // slider.on('breakpoint', function(event, slick, direction){});

			  	slider.slick({
				  	"autoplaySpeed" : 5000,
				  	"autoplay" : false,
				  	"dots" : false,
				  	"arrows" : true,
						"responsive": [
							{
								"breakpoint" : 992,
								"settings" : {
							  	"dots" : false
								}
							}
						]
				  });

				}
			}
		});

  }

	testQueue.queueStart();

	/* ---------------------------------------------------------------- */

	// people module

	/*
	-> PeopleBios -> source/js/classes.js -> assets/js/classes.min.js
	*/
	let peopleBiosInstance = new PeopleBios({
		"bioOverlayModuleSelector" : ".bioOverlayTrigger",
		"carouselSelector" : ".peopleSlickSlider",
		"imageBinSelector" : ".imageBin",
		"overlayFrameSelector" : "#bioOverlay",
		"slickSliderSelector" : ".ssp",
		"peopleItemsSelector" : ".people .item",
		"afterSlickSliderInit" : function() {
			console.log("-> afterSlickSliderInit");
		},
		"afterSlickSliderBreakpoint" : function() {
			console.log("-> afterSlickSliderBreakpoint");
		}
	});

	/* ---------------------------------------------------------------- */

	// work landing page - load more case studies

	if ( $('#moreCaseStudies').length > 0 ) {

		let workControlClipper = $('#moreCaseStudies');
		let caseStudyList = $('#caseStudyList');
		let itemTemplate = $('#itemTemplate');
		let visibleCaseStudies = caseStudyList.attr('data-list');
		let pageSize = caseStudyList.attr('data-pagesize');
		let clipperIndex = caseStudyList.attr('data-clipper');
		let players = [];

		$('#moreCaseStudies').on('click',function(e){
			workControlClipper.addClass('pending');
		    $.ajax({
		        url: localize_vars.ajaxurl,
		        type: "GET",
		        data: {
					'action': 'morework',
					'visible' : caseStudyList.attr('data-list'),
					'preferred' : caseStudyList.attr('data-preferred-order'),
					"pagesize" : pageSize
		        },
		        dataType: 'json',
		        success: function (data) {

		        	// console.log(data);

		            // hide load more control if there are no more items to load
		            if ( !data.show_loader ) {
		            	workControlClipper.addClass('closed');
		            }

		            // check to see if number of new items is less than page size
		            if ( data.new_item_count < data.items_per_page) {
		            	workControlClipper.addClass('closed');
		            }

		            // add clipper element to page

		            let newClipper = $('<div></div>')
		            .addClass('clipper')
		            .attr({"id" : "clipper-" + clipperIndex});

		            // iterate over items in response
		            let n = 0;
		            while (n < data.items.length) {
		            	let thisItem = data.items[n];

		            	// get template
		            	let newItem = itemTemplate.clone();
		            	newItem.addClass('caseStudy');
		            	// insert data into template
		            	newItem.attr({"id" : "casestud-" + thisItem.id});
		            	newItem.find('h2').text(thisItem.client);
		            	newItem.find('h3').text(thisItem.campaign);
		            	newItem.find('a.csLink').attr({"href" : thisItem.url});

		            	let backgroundNode = newItem.find('.background');
		            	let newFigure = $('<figure></figure>');

		            	// handle inserting videos
		            	if ( thisItem.hasvideo === 'yes' ) {
	            			// console.log("video format: %s source: %s", thisItem.format, thisItem.source);

	            			backgroundNode.attr({
	            				"data-format" : thisItem.format
	            			});

	            			let w = null,h = null;
	            			switch( thisItem.format ) {
	            				case "vimeo" : {
	            					// data-width="1292" data-height="727"
	            					w = 1292;
	            					h = 727;
	            				} break;
	            				case "inline" : 
	            				case "youtube" : {
	            					// data-height="1080" data-width="1920"
	            					w = 1920;
	            					h = 1080;
	            				} break;
	            			}
	            			let videoWrapper = $('<div></div>').addClass('videoWrapper').addClass(thisItem.format + 'Wrapper');
	            			videoWrapper.attr({
	            				"data-format" : thisItem.format,
	            				"data-source" : thisItem.source,
	            				"data-mode" : "hover",
	            				"data-width" : w,
	            				"data-height" : h
	            			});
	            			let pr = $('<div></div>').addClass('pr');

			            	switch ( thisItem.format ) {
			            		case "vimeo" : {
			            			// vimeo video wrapper
			            			/*
									<div class="videoWrapper vimeoWrapper" data-mode="hover" data-width="1292" data-height="727" data-format="vimeo" data-source="<?php echo $source; ?>">
										<div class="pr">
											<iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $source; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1" frameborder="0" allow="autoplay;" title="<?php echo $metadata['client']. ' - '.$metadata['campaign']; ?>"></iframe>
										</div>
									</div>
			            			*/

			            			let iframe = $('<iframe></iframe>').addClass('vimeo').attr({
			            				"src" : "https://player.vimeo.com/video/" + thisItem.source + "?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0",
										"frameborder" : "0",
										"allow" : "autoplay;"
			            			});
			            			
									pr.append(iframe);
			            			videoWrapper.append(pr);
			            			backgroundNode.append(videoWrapper);

			            		} break;
			            		case "youtube" : {
			            			// not yet implemented
			            			let iframe = $('<iframe></iframe>').addClass('youtube');
									pr.append(iframe);
			            			videoWrapper.append(pr);
			            			backgroundNode.append(videoWrapper);
			            		} break;
			            		case "inline" : {
			            			// 
			            			videoWrapper.append(pr);
			            			backgroundNode.append(videoWrapper);
			            		} break;
			            	}

		            	} else {
		            		backgroundNode.addClass('noVideo');
		            	}

		            	// desktop & mobile images
		            	// maybe add queue-load 
		            	let noMobile = ( thisItem.mobile === 'NOMOBILE');

		            	if ( !noMobile) {
		            		let desktopImage = $('<img></img')
		            		.attr({
		            			"src" : thisItem.desktop
		            		})
		            		.addClass('desktop');

		            		let mobileImage = $('<img></img')
		            		.attr({
		            			"src" : thisItem.mobile
		            		})
		            		.addClass('mobile');

		            		newFigure
		            		.append(desktopImage)
		            		.append(mobileImage);
		            		backgroundNode.append(newFigure);
		            		newItem.append(backgroundNode);
		            	} else {

		            		let desktopImage = $('<img></img')
		            		.attr({
		            			"src" : thisItem.desktop
		            		});

		            		newFigure.append(desktopImage);
		            		backgroundNode.append(newFigure);
		            		newItem.append(backgroundNode);
		            	}

		            	newItem.addClass('loaded');
		            	// insert into clipepr
		            	newClipper.append(newItem);
		            	n++;
		            }
		            // news items built and ready to be inserted into page
		            workControlClipper.removeClass('pending');

		            // insert clipper element into case study list
		            caseStudyList.append(newClipper);

		            // set up videos to new items
		            // Autoscale
		            // bindvideos
		            let newbindVideos = new BindVideos({});

		            // update shown items list in #caseStudtList
		            caseStudyList.attr({"data-list" : data.ids.join(',')});
		            caseStudyList.attr({"data-preferred-order" : data.preferred_order.join(',')});

		            // set up clipper index for next item
		            clipperIndex++;
		            caseStudyList.attr({"data-clipper" : clipperIndex });
		        }
		    });
		});
	}

	/* ---------------------------------------------------------------- */

	// who we are page

	// intro
	let whoWeAreIntroModule = $('section.whoWeAreIntro');
	let mergeVideo = $('#mba-stack-merge');

	// start mba + stack merger video on mobile when user scrolls down
	if (mergeVideo.length > 0 && $(window).width() < 993) {
	// if (mergeVideo.length > 0 ) {
		console.log('merge video mobile');

		window.setTimeout(function(){

			let mergeVideoScroll = new GenericScrollHandler({
				"trigger" : whoWeAreIntroModule,
				// "trigger" : $('section.fuse .videoClipn'),
				"subject" : $('section.fuse'),
				"animateOnce" : false, // animation bound to scroll
				"getDimensions" : (instance) => {
					instance.top = instance.trigger.position().top;

					instance.height = instance.trigger.height();
					instance.bottom = instance.top + instance.height;

					// $('body').append( $('<div></div>').addClass('mark').css({ "top" : instance.top + "px", "background-color" : "#0f0", }) );
					// $('body').append( $('<div></div>').addClass('mark').css({ "top" : instance.bottom + "px", "background-color" : "#f00", }) );

					instance.start = instance.top - (instance.wh / 2); // top of module halfway up browser window
					instance.end = instance.top; // bottom of module halfway up browser window
					// instance.end = instance.bottom - (instance.wh / 2); // bottom of module halfway up browser window

					// $('body').append( $('<div></div>').addClass('mark').css({"top" : instance.start + "px","background-color" : "#0f0",}) );
					// $('body').append( $('<div></div>').addClass('mark').css({"top" : instance.end + "px","background-color" : "#f00",}) );

					instance.started = false;
				},
				"monitor" : (instance,scrolltop) => {
					let percent = 0;

					if ( scrolltop > instance.start && !instance.started) {
						instance.update(1.0);	
					}
				},
				"update" : (instance,percent) => {
					console.log('merge video started');
					instance.started = true;

					$('#mba-stack-merge video').get(0).play().then(function(){
						// $('#mba-stack-merge').parents('.background').addClass('videoLoaded');
					}).catch(function(){ });
				}
			});
		},10);
	}

	// CXM block
	if ( $('section.cxmv3').length > 0 ){
	
		// CXM technology paging
		let sectionCXM = $('section.cxmv3');
		let controls = sectionCXM.find('.pages li');

		controls.on('click',function(e){
			if ( !$(this).hasClass('active') ){
				let cbitem = $(this).parents('.cbitem');
				let pages = cbitem.find('.pages .page');
				let controls = cbitem.find('.pages li');
				let pageIndex = controls.index($(this));

				pages.not(pageIndex).removeClass('active');
				pages.eq(pageIndex).addClass('active');

				controls.not(pageIndex).removeClass('active');
				controls.eq(pageIndex).addClass('active');
			}
		});

		// CXM scroll interaction
		// only animates the text blocks atm
		// wanted: tie graph animation to scroll
		/*
		-> BuggerScroller -> source/js/classes.js -> assets/js/classes.min.js
		*/

		/* */
		let cxmBugger = new BuggerScroller({
			"trigger" : $('.cxmv3'),
			"phases" : [
					{
						"id" :  "cb-c",
						"action" : "fadein",
						"start" : 0.0,
						"stop" : 16.6
					},
					{
						"id" :  "cb-c",
						"action" : "fadeout",
						"start" : 16.6,
						"stop" : 33.2
					},
					{
						"id" :  "cb-d",
						"action" : "fadein",
						"start" : 33.2,
						"stop" : 49.8
					},
					{
						"id" :  "cb-d",
						"action" : "fadeout",
						"start" : 49.8,
						"stop" : 66.4
					},
					{
						"id" :  "cb-t",
						"action" : "fadein",
						"start" : 66.4,
						"stop" : 85.0
					},
					{
						"id" :  "cb-t",
						"action" : "fadeout",
						"start" : 85.0,
						"stop" : 100.0
					}
				]
		});
		/* */

	  // CXM module map scroll animation
	  // PageAnimations.lottieScroller -> source/js/classes.js -> assets/js/classes.min.js
	}

	// fuse block
	if ( $('section.fuse').length > 0 ) {
		let fuseModule = $('section.fuse');

		/*
		FuseVideoScroll -> classes.js
		*/

		let fuseVideoAnimation = new FuseVideoScroll({
			"target" : "section.fuse .videoWrapper",
			"video" : "section.fuse .videoWrapper video"
		});

		let fuseAnimation = new GenericScrollHandler({
			"trigger" : fuseModule,
			// "trigger" : $('section.fuse .videoClipn'),
			"subject" : $('section.fuse'),
			"animateOnce" : false, // animation bound to scroll
			"getDimensions" : (instance) => {
				instance.top = instance.trigger.position().top;

				instance.height = instance.trigger.height();
				instance.bottom = instance.top + instance.height;

				// $('body').append( $('<div></div>').addClass('mark').css({ "top" : instance.top + "px", "background-color" : "#0f0", }) );
				// $('body').append( $('<div></div>').addClass('mark').css({ "top" : instance.bottom + "px", "background-color" : "#f00", }) );

				instance.start = instance.top - (instance.wh / 2); // top of module halfway up browser window
				instance.end = instance.top; // bottom of module halfway up browser window
				// instance.end = instance.bottom - (instance.wh / 2); // bottom of module halfway up browser window

				// $('body').append( $('<div></div>').addClass('mark').css({"top" : instance.start + "px","background-color" : "#0f0",}) );
				// $('body').append( $('<div></div>').addClass('mark').css({"top" : instance.end + "px","background-color" : "#f00",}) );
			},
			"monitor" : (instance,scrolltop) => {
				let percent = 0;

				instance.state = false;

				if ( scrolltop < instance.start ) {
					// first frame
					instance.update(0.0);
				}

				if ( scrolltop > instance.start && scrolltop < instance.end ) {

					let percent =  (scrolltop - instance.start ) / ( instance.end - instance.start );
					instance.state = true;
					instance.update(percent);	
				}

				if ( scrolltop > instance.end ) {
					// last frame
					instance.update(1.0);
				}
			},
			"update" : (instance,percent) => {
				fuseVideoAnimation.scroll(percent);
			}
		});
	}

	// tools block
	// tools horizontal scroller
	if ( $('.toolsWrapper1').length > 0 ) {

		/*
		let toolsWindow = $('.toolsWrapper1');
		let toolsTrack = $('.toolsWrapper2');
		let toolsCount = $('.toolsWrapper2 .item').length;
		let toolsStep = Math.floor(toolsTrack.width() / toolsCount);
		let toolindex = 0;

		$('.toolsWrapper1').on('scroll',(e) => {
			let x = e.target;
			let hScroll = $(x).scrollLeft();
			toolindex = Math.floor(hScroll / toolsStep);
		})

		// go to next panel, loop back to start
		$('button.nextItem').on('click', (e) => {
			toolindex++;
			if(toolindex === toolsCount) {
				toolindex = 0;
			}

			$('.toolsWrapper1').animate({
				"scrollLeft" : (toolindex * toolsStep)
			},500);

		});
		*/
	}

	// 'Group' module map scroll animation
	// PageAnimations.lottieScroller -> source/js/classes.js -> assets/js/classes.min.js
	
	/* ---------------------------------------------------------------- */

	// case study pages
	// check for carousel - apply other animations AFTER the carousels have initiialised

	// statistics module
	if ($('.case-study-awards').length > 0 ) {
		/*
		let awardAnimations = [];
		let awardPoints = $('.case-study-awards .point');
		awardPoints.each(function(i){
			awardAnimations.push(new AwardAnimation({
				"point" : $(this)
			}));
		});
		*/
	}

	// image carousel module
	if ($('.case-study-image_carousel').length > 0) {
		$('.focus').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.gallery'
		});

		$('.gallery').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.focus',
			dots: false,
			arrows: false,
			centerMode: true,
			focusOnSelect: true
		});

	}

	
	/* ---------------------------------------------------------------- */

	// news landing page

	/*
	item{} parameters.item
		category: "news"
		date: "19.07.21"
		has_external_link: "external"
		id: 194
		image: "http://local.mbastack/wp-content/uploads/2021/07/news03.jpg"
		link_caption: "watch on campaignlive.co.uk"
		link_url: "http://campaignlive.co.uk"
		permalink: "http://local.mbastack/mba-stack/"
		synopsis: "MBA and stack join forces"
		title: "MBA + Stack"
	target parameters.target
	*/

	// NewsLoader -> source/js/classes.js
	let newsPageLoaders = [];
	$('.latest-news').each(function(i){
		newsPageLoaders.push(new NewsLoader({
			"newsmodule" : $(this)
		}));
	});

	$('.latest-news .filter .control').on('click',function(e){
		e.preventDefault();
		let filter = $(this).parents('.filter');

		filter.toggleClass('open');
	});

	$('.latest-news .filter ul li button').on('click',function(e){
		e.preventDefault();
		let filter = $(this).parents('.filter');

		filter.removeClass('open');
	});

	/* ---------------------------------------------------------------- */

	if ($('.vv').length > 0 ) {
		let vv = $('.vv');
		let vv_width = vv.width();
		let vv_height = vv.height();
		let source = vv.attr('data-source');
		let width = vv.attr('data-width');
		let height = vv.attr('data-height');


		/*
		<iframe
			class="vimeo"
			src="https://player.vimeo.com/video/649001320?autoplay=1&amp;loop=1&amp;autopause=0&amp;controls=0&amp;background=1&amp;color=000000&amp;portrait=1"
			frameborder="0"
			allow="autoplay;"
			title="MBAstack"
			data-ready="true"
			width="1672"
			height="540"
		>
		</iframe>
		*/
		let scale = 0;
		let nuWidth = 0;
		let nuHeight = 0;
		let nuIframe = $('<iframe></iframe>')
		.attr({
			"src" : "https://player.vimeo.com/video/649001320?autoplay=1&amp;loop=1&amp;autopause=0&amp;controls=0&amp;background=1&amp;color=000000&amp;portrait=1",
			"width" : "1672",
			"height" : "540",
			"frameborder" : "0",
			"allow" : "autoplay",
			"title" : "Our Values",
			"data-ready" : "true"
		})		

		vv.append(nuIframe);

		let scaleVV = function ( vv_width ) {
			if (  vv_width >= 1672 ) {
				// scale video to fit width	
				// height increases to maintain aspect ratio
				scale = vv_width / 1672;
				nuWidth = vv_width;
				nuHeight = scale * 540;
				$('.vv iframe').css({ "width" : nuWidth + "px", "height" : nuHeight + "px" });
			}

			if ( vv_width < 1672 && vv_width > 700 ) {
				// constant height, aspect ration changes, video pinned to left hand edeg of browser
				nuWidth = 1672;
				nuHeight = 540;
				$('.vv iframe').css({ "width" : nuWidth + "px", "height" : nuHeight + "px" });
			}	

			if ( vv_width <= 700 ) {
				// height decreases to maintain aspect ratio 540/700 77.1%
				// scale to fraction of width 700 / 1672 = 0.418660
				scale = vv_width / (1672 * 0.418660);
				nuWidth = vv_width / 0.418660;
				nuHeight = scale * 540;
				$('.vv iframe').css({ "width" : nuWidth + "px", "height" : nuHeight + "px" });
			}
		};

		scaleVV( vv_width );

		$(window).on('resize',() => {
			vv = $('.vv');
			vv_width = vv.width();
			vv_height = vv.height();

			scaleVV( vv_width );			

		});

	}

	/* ---------------------------------------------------------------- */

});

/* ---------------------------------------------------------------- */
