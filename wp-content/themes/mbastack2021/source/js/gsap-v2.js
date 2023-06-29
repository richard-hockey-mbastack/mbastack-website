	// if ( $('.latest-news').length > 0 || $('.otherPosts').length > 0 ) {

		var batchAnimation = function(items, batchSize, animation) {

				// https://codepen.io/GreenSock/pen/823312ec3785be7b25315ec2efd517d8

				// usage:
				batch(items, {
				  interval: 0.1, // time window (in seconds) for batching to occur. The first callback that occurs (of its type) will start the timer, and when it elapses, any other similar callbacks for other targets will be batched into an array and fed to the callback. Default is 0.1
				  batchMax: batchSize,   // maximum batch size (targets)

				  onEnter: batch => gsap.to(batch, animation.e1)
				  // onLeave: batch => gsap.set(batch, animation.l1),
				  // onEnterBack: batch => gsap.to(batch, animation.e2),
				  // onLeaveBack: batch => gsap.set(batch, animation.l2)
				  // you can also define things like start, end, etc.
				});

				// the magical helper function (no longer necessary in GSAP 3.3.1 because it was added as ScrollTrigger.batch())...
				function batch(targets, vars) {

					// console.log(targets, vars);

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

	// }

	const types = /(twoColumnMixed|sharedCaseStudies|latestClients|latest-news|people|homeValues|singleColumn|tools|cxmv3|nimble|group|twoColumnParallax|otherPosts|trinity|awards|fullWidthImage)/;
	const winheight = $(window).height();
	const threequarterheight = Math.floor( winheight * 0.75);
	const halfheight = Math.floor( winheight * 0.5);
	const quarterheight = Math.floor( winheight * 0.25);

	const boxes = gsap.utils.toArray('.module');
	boxes.forEach(box => {
		let moduleClass = box.getAttribute('class');

		// console.log( "box: '%s'", box.getAttribute('class') );
		let matches = moduleClass.match(types);
		if ( matches ) {
			moduleType = matches[0];

			if ( moduleType === 'homeBanner' || moduleType === 'pageBanner' ) {
				console.log('homeBanner');

				console.log("box height %s", $(box).height() );
			} else {
				// console.log( "box: '%s'", moduleType );

				switch ( moduleType ) {

					case 'twoColumnMixed' : {
						console.log('twoColumnMixed');
						let thisBox = $(box);

						// text column
						let textColumn = thisBox.find('.textColumn');
						// image column
						let imageColumn = thisBox.find('.imageColumn');

					  gsap.to(textColumn, { 
					    y: -500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + halfheight,
				        "end" : () => 'bottom-=' + winheight,
				        markers : false,
					      trigger: box,
					      scrub: true,
					    }
					  })

					  gsap.to(imageColumn, { 
					    y: -500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + halfheight,
				        "end" : () => 'bottom-=' + winheight,
					      trigger: box,
					      scrub: true,
					    }
					  })
					} break;

					case 'sharedCaseStudies' : {
						console.log('sharedCaseStudies');
						let thisBox = $(box);
					} break;

					case 'latestClients' : {
						console.log('latestClients');
						// let thisBox = $(box);

						batchAnimation('.latestClients .item', 6, {
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
						console.log('latest-news / otherPosts');
						let thisBox = $(box);

						batchAnimation('.newsitem', 3, {
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});
					} break;

					case 'people' : {
						console.log('people');
						let thisBox = $(box);

						// different behaviour on home page and 'People' page

						if ( thisBox.hasClass('peopleSlickSlider') ) {
							console.log('carousel');
						} else {
							console.log('grid');

							// intro header
							let headrSub = thisBox.find('.teamIntro');
						  gsap.to(headrSub, { 
						    y: -500,
						    autoAlpha: 1,
						    scrollTrigger: {
					        "start" : () => 'top-=' + halfheight,
					        "end" : () => 'bottom-=' + winheight,
						      trigger: box,
						      scrub: false,
						    }
						  })

							batchAnimation('.people .item', 3, {
								"e1" : {
									autoAlpha: 1,
									y:-500,
									stagger: 0.15,
									overwrite: true
								}
							});
						}
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
					    y: -500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + halfheight,
				        "end" : () => 'bottom-=' + winheight,
				        // "start" : () => 'top-=' + Math.floor( $(window).height() / 2 ),
				        // "end" : () => 'bottom-=' + $(window).height(),
					      trigger: box,
					      scrub: false,
					      markers : true
					    }
					  })

					} break;

					case 'tools' : {
						console.log('tools');
						let thisBox = $(box);

						// maybe add stagger to intro and items

						// intro header
						let headrSub = thisBox.find('header.sub');
					  gsap.to(headrSub, { 
					    y: -500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + halfheight,
				        "end" : () => 'bottom-=' + winheight,
					      trigger: box,
					      scrub: false,
					    }
					  })

						batchAnimation('.tools .item', 5,{
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});

						/*
						items.each(function(i){
						  gsap.to($(this), { 
						    y:-500,
						    autoAlpha: 1,
						    scrollTrigger: {
					        "start" : () => 'top-=' + halfheight,
					        "end" : () => 'bottom-=' + winheight,
						      trigger: box,
						      scrub: true,
						    }
						  })
						});
						*/
					} break;

					case 'cxm' : {
						console.log('cxmv3');
						let thisBox = $(box);


						// -> BuggerScroller
					} break;

					case 'nimble' : {
						console.log('nimble');
						let thisBox = $(box);

						batchAnimation('.nimble .copy h3,.nimble .copy p.ic, .nimble .copy .clipper', 3,{
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});
					} break;

					case 'group' : {
						console.log('group');
						let thisBox = $(box);

						batchAnimation('.group .mapgraph, .group .msqinfo', 3,{
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});
					} break;

					case 'twoColumnParallax' : {
						console.log('twoColumnParallax');
						let thisBox = $(box);

						batchAnimation('.twoColumnParallax .image, .twoColumnParallax .text', 2, {
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
						console.log('awards');
						let thisBox = $(box);

						// intro header
						let headrSub = thisBox.find('.introbox');
					  gsap.to(headrSub, { 
					    y: -500,
					    autoAlpha: 1,
					    scrollTrigger: {
				        "start" : () => 'top-=' + halfheight,
				        "end" : () => 'bottom-=' + winheight,
					      trigger: box,
					      scrub: false,
					    }
					  })

						batchAnimation('.awards .award', 3, {
							"e1" : {
								autoAlpha: 1,
								y:-500,
								stagger: 0.15,
								overwrite: true
							}
						});
					} break;

					case 'fullWidthImage' : {
						console.log('fullWidthImage');
						let thisBox = $(box);
					} break;

				}
			};
		}
	});
