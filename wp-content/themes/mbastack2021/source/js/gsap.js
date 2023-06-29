	/*
	gsap.to(".module", {
	  scrollTrigger: ".module", // start the animation when ".box" enters the viewport (once)
	  x: 500
	});
	*/

	/*
	ScrollTrigger.create({
		trigger: ".module",
		start: "top top",
		endTrigger: ".module",
		end: "bottom 50%+=100px",
		onToggle: self => console.log("toggled, isActive:", self.isActive),
		onUpdate: self => {
			console.log("progress:", self.progress.toFixed(3), "direction:", self.direction, "velocity", self.getVelocity());
		}
	});
	*/

	/*
	gsap.to('#module-two_column-1 .textColumn', { autoAlpha: 1,
    scrollTrigger: {
        trigger: '#module-two_column-1',
        start: 'top top+=100', 
        markers: true,
        scrub: true,
    }
	});
	*/

	/* ------------------------------- */
	// https://greensock.com/st-mistakes/
	/* ------------------------------- */

	/* apply animation to multiple elements in page */
	// const types = /(homeBanner|twoColumnMixed|sharedCaseStudies|latestClients|latest-news|people|homeValues|singleColumn|pageBanner)/;
	const types = /(twoColumnMixed|sharedCaseStudies|latestClients|latest-news|people|homeValues|singleColumn|tools|cxmv3|nimble|group|twoColumnParallax|otherPosts)/;

	/*
	case study modules
		case-study-intro [.sidebar, .main]
		case-study-fullwidth_image
		case-study-fullwidth_video
		case-study-two_column_media
		case-study-single_column_text [h3, h4, p]
		case-study-awards [ header[h2,h3], .col-md-3[h4,p] ]
		case-study-image_carousel ** slickSlider Carousel widget
		sharedCaseStudies / case-study-more_case_studies [.caseStudy]
	*/
	
	const boxes = gsap.utils.toArray('.module');

	let batchAnimation = function(items, animation) {

			// https://codepen.io/GreenSock/pen/823312ec3785be7b25315ec2efd517d8

			// usage:
			batch(items, {
			  interval: 0.1, // time window (in seconds) for batching to occur. The first callback that occurs (of its type) will start the timer, and when it elapses, any other similar callbacks for other targets will be batched into an array and fed to the callback. Default is 0.1
			  batchMax: 3,   // maximum batch size (targets)

			  onEnter: batch => gsap.to(batch, animation.e1)
			  // onLeave: batch => gsap.set(batch, animation.l1),
			  // onEnterBack: batch => gsap.to(batch, animation.e2),
			  // onLeaveBack: batch => gsap.set(batch, animation.l2)

			  // you can also define things like start, end, etc.
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

	};

	boxes.forEach(box => {
		let moduleClass = box.getAttribute('class');


		let matches = moduleClass.match(types);
		if ( matches ) {
			moduleType = matches[0];

			console.log( box.getAttribute('class') );

			if ( moduleType === 'homeBanner' || moduleType === 'pageBanner' ) {
				console.log('homeBanner');

				console.log("box height %s", $(box).height() );
			} else {
				switch ( moduleType ) {
					case 'twoColumnMixed' : {
						console.log('twoColumnMixed');
						let thisBox = $(box);

						// text column
						let textColumn = thisBox.find('.textColumn');
					  gsap.to(textColumn, { 
					    x: 500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ),
				        "end" : () => 'bottom-=' + $(window).height(),
					      trigger: box,
					      scrub: true,
					    }
					  })

						// image column
						let imageColumn = thisBox.find('.imageColumn');
					  gsap.to(imageColumn, { 
					    x: -500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ),
				        "end" : () => 'bottom-=' + $(window).height(),
					      trigger: box,
					      scrub: true,
					    }
					  })
					} break;

					case 'sharedCaseStudies' : {
						console.log('sharedCaseStudies');
						let thisBox = $(box);

						// .caseStudy
						let caseStudies = thisBox.find('.caseStudy');
						caseStudies.each(function(i){
							let item = $(this);
						  gsap.to(item, { 
						    y: 500,
						    autoAlpha: 1,
						    scrollTrigger: {
									// "start" : () => 'top',
									// "end" : () => 'bottom',
					        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ),
					        "end" : () => 'bottom-=' + $(window).height(),
						      trigger: item,
						      scrub: false,
						    }
						  })

							/*
							$(this).css({
							  '-webkit-transform' : 'scale(0.5)',
							  '-moz-transform'    : 'scale(0.5)',
							  '-ms-transform'     : 'scale(0.5)',
							  '-o-transform'      : 'scale(0.5)',
							  'transform'         : 'scale(0.5)'
							});
							*/
						});

					} break;

					case 'latestClients' : {
						console.log('latestClients');
						let thisBox = $(box);

						// .item
						batchAnimation('.latestClients .item',{
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});

						/*
						let clients = thisBox.find('.item');
						clients.each(function(i){
							let item = $(this);
						  gsap.to(item, { 
						    y: 500,
						    autoAlpha: 1,
						    scrollTrigger: {
					        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ),
					        "end" : () => 'bottom-=' + $(window).height(), // end animation when bottom ot target crosses bottom of viewport
						      trigger: item,
						      toggleClass: 'enable',
						      scrub: false,
						    }
						  })
						});
						*/
					} break;

					case 'latest-news' : {
						console.log('latest-news');
						let thisBox = $(box);

						batchAnimation('.latest-news .newsitem',{
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});
					} break;

					case 'otherPosts' : {
						console.log('otherPosts');
						let thisBox = $(box);

						// .newsitem
						batchAnimation('.otherPosts .newsitem',{
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});

						/*
						let posts = thisBox.find('.newsitem');
						posts.each(function(i){
							let item = $(this);

						  gsap.to(item, { 
						    y: 500,
						    autoAlpha: 1,
						    scrollTrigger : {
					        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ),
					        "end" : () => 'bottom-=' + $(window).height(),
						      trigger: item,
						      toggleClass: 'enable',
						      scrub: false,
						    }
						  })
						});
						*/
					} break;

					case 'people' : {
						console.log('people');
						let thisBox = $(box);

						// ** slickSlide carousel - start animation AFTER slickslide has finished initialising
						// .item

						// ** applying an animation to .item iterferes with the slickSlider carousel
						// maybe apply the animation to the children of the .item, figure H2,3,4,a

						// apply animation to 'People' page only, not 'people' module on home page
						if ( !thisBox.hasClass('peopleSlickSlider') ) {
							batchAnimation('.people .item',{
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});

						}						
						/*
						let people = thisBox.find('.item');
						people.each(function(i){
							let item = $(this);
						  gsap.to(item, { 
						    // y: 500,
						    // autoAlpha: 1,
						    scrollTrigger: {
					        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ), // start animation when item is half of viewport height from top of viewport
					        "end" : () => 'bottom',
						      trigger: item,
						      toggleClass: 'enable',
						      scrub: false,
						    }
						  })
						});
						*/
					} break;
					
					case 'homeValues' : {
						console.log('homeValues');
						let thisBox = $(box);

					} break;
					
					case 'singleColumn' : {
						console.log('singleColumn');
						let thisBox = $(box);

						let textColumn = thisBox.find('.ba');
					  gsap.to(textColumn, { 
					    x: 500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + Math.floor( $(window).height() ),
				        // "start" : () => 'top',
				        "end" : () => 'bottom-=' + Math.floor( $(window).height() / 2 ),
					      trigger: box,
					      scrub: true,
					    }
					  })

					} break;

					case 'tools' : {
						console.log('tools');
						let thisBox = $(box);

						// intro header
						let headrSub = thisBox.find('header.sub');
					  gsap.to(headrSub, { 
					    x: 500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + Math.floor( $(window).height() / 4 ), // start animation when top of target crosses bottom of viewport
				        "end" : () => 'bottom-=' + $(window).height(), // end animation when bottom ot target crosses bottom of viewport
					      trigger: box,
					      scrub: true,
					    }
					  })

					  // items
					  let itemsOdd = thisBox.find('.item:nth-child(odd)');
					  let itemsEven = thisBox.find('.item:nth-child(even)');

						itemsOdd.each(function(i){
						  gsap.to($(this), { 
						    x: 500,
						    y:-500,
						    autoAlpha: 1,
						    scrollTrigger: {
					        "start" : () => 'top-=' + Math.floor( $(window).height() / 3.8 ), // start animation when top of target crosses bottom of viewport
					        "end" : () => 'bottom-=' + Math.floor( $(window).height() ), // end animation when bottom ot target crosses bottom of viewport
						      trigger: box,
						      scrub: true,
						    }
						  })
						});

						itemsEven.each(function(i){
						  gsap.to($(this), { 
						    x: -500,
						    y:-500,
						    autoAlpha: 1,
						    scrollTrigger: {
					        "start" : () => 'top-=' + Math.floor( $(window).height() / 4 ), // start animation when top of target crosses bottom of viewport
					        "end" : () => 'bottom-=' + Math.floor( $(window).height() ), // end animation when bottom ot target crosses bottom of viewport
						      trigger: box,
						      scrub: true,
						    }
						  })
						});

					} break;

					case 'cxm' : {
						console.log('cxmv3');
						let thisBox = $(box);

						// -> BuggerScroller

					} break;

					case 'nimble' : {
						console.log('nimble');
						let thisBox = $(box);
					} break;

					case 'group' : {
						console.log('group');
						let thisBox = $(box);
					} break;

					case 'twoColumnParallax' : {
						console.log('twoColumnParallax');
						let thisBox = $(box);

						let imageFirst = thisBox.hasClass('first-row-image-text');
						// imageFirst true
						// first row
						// .image left
						// .text right
						// second row
						// .image right
						// .text left

						// imageFirst false
						// first row
						// .image right
						// .text left
						// second row
						// .image left
						// .text right

						// following rows alternate left/right 

						// HTML structure
						// .row
						// .row .image
						// .row .text
						// ...

					  // let itemsOdd = thisBox.find('.item:nth-child(odd)');
					  // let itemsEven = thisBox.find('.item:nth-child(even)');


					} break;
				}
			}
		}
	});

	// animate module 'topclip' headers
	const headers = gsap.utils.toArray('.topclip');

	headers.forEach(header => {
		// module header
		let topClip = $(header);
		let parentModule = topClip.parents('section.module');
	  gsap.to(topClip, { 
	    y: 25,
	    scrollTrigger: {
        "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ), // start animation when top of target crosses bottom of viewport
        "end" : () => 'bottom-=' + $(window).height(), // end animation when bottom ot target crosses bottom of viewport
	      trigger: parentModule,
	      scrub: true, 
	    }
	  })

	  /*
	  	with scrub set to true, resets the header position as you scroll past the module
	  	header is only fully translated when the box is at 50% scroll

			with scrub set to falsem, the translation is applied once the container is scrolled into view and does not reset
			requires a second animation to reset the header
	  */
	});
