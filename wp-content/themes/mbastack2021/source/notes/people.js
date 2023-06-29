		let people = [];
		let peopleCount = 0;
		let currentPerson = -1;
		let peopleImages = [];
		let imageBin = $('.imageBin');

		// update dekstop people bio overlay when it is opened or the pagin buttons are used
		let updateBioOverlay = function( person ) {
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
			var tt = $('<div/>').html(item.bio).text();
			$('#bioCopy').empty().append( $('<p></p>').html(tt) );

			// show hide socail media links
			let mbSocial = overlay.find('.socialLinks');

			let hasLinkedin = ( item.linkedin !== '' );
			let hasTwitter = ( item.twitter !== '' );
			let hasInstagram = ( item.instagram !== '' );
			let hasFacebook = ( item.facebook !== '' );
			let hasEmail = ( item.email !== '' );
			let hasPhone = ( item.phone !== '' );

			// let hasSocialLinks = hasLinkedin || hasTwitter || hasInstagram || hasFacebook || hasEmail || hasPhone;
			let hasSocialLinks = hasLinkedin || hasTwitter || hasInstagram;
			if ( hasSocialLinks ) {

				let linkedinNode = mbSocial.find('.linkedin');
				if ( hasLinkedin ) {
					linkedinNode.attr({"href" : item.linkedin}).show();
				} else {
					linkedinNode.hide();
				}

				let twitterNode = mbSocial.find('.twitter');
				if ( hasTwitter ) {
					twitterNode.attr({"href" : item.twitter}).show();
				} else {
					twitterNode.hide();
				}

				let instagramNode = mbSocial.find('.instagram');
				if ( hasInstagram ) {
					instagramNode.attr({"href" : item.instagram}).show();
				} else {
					instagramNode.hide();
				}

				mbSocial.find('.contactForename').text(item.forename)
				mbSocial.show();

			} else {
				mbSocial.hide();
			}
			
		}

		// gte person info based on person id
		let getPerson = function(id) {
			let n = 0;
			while (n < peopleCount ) {
				if ( ('person-' + people[n].slug) === id ) {
					return {
						"person" : people[n],
						"index" : n
					};
				}
				n++;
			}
			return false;
		}

		let getPersonBySlug = function(slug) {
			let n = 0;
			while (n < peopleCount ) {
				if ( people[n].slug === slug ) {

					return {
						"person" : people[n],
						"index" : n
					};
				}
				n++;
			}
			return false;
		}

		let personClick = function(node){
			let personNode = node;
			let subject = personNode.attr('id');

			let personXN = getPerson(subject);
			currentPerson = personXN.index;

			switch( pageSize ){
				case 'desktop' : {
					// populate overlay
					
					updateBioOverlay(personXN);

					$('html, body').stop().animate({"scrollTop" : $('body').offset().top }, 300);
					$('.bioOverlay').addClass('open');

				} break;
				case 'mobile' : {
					personNode.toggleClass('open');
				} break;
			}

		};

	// bio overlay
	if ( $('.people.bioOverlayTrigger').length > 0 ) {

		// --------------------------------------------------------------------------------------------------------
		// people bio data AJAX load START
		// --------------------------------------------------------------------------------------------------------

		// load metadata from server via AJAX
		// combine in image preload
    $.ajax({
        url: localize_vars.ajaxurl,
        type: "GET",
        data: {
					"action" : "peoplepreload",
					"context" : $('section.people').attr('data-context')
        },
        dataType: 'json',
        success: function (data) {
        		people = data.people;
        		peopleCount = people.length;

        		/// open overlay on People page if URL has name anchor
						if (window.location.href.indexOf('people') !== -1 ){
							// extract anchor -> person
							// #([a-z\-]+)

							const personFilter = /people\/#([a-z\-]+)/;
							let result = personFilter.exec(window.location.href);
							if(result) {
								// open bio overlay;
								let personToby = getPersonBySlug(result[1]); // needs to run AFTER the AJAX people preload

								switch(pageSize) {
									case 'desktop' : {
										updateBioOverlay(personToby);
										$('html, body').stop().animate({"scrollTop" : $('body').offset().top }, 300);
										$('.bioOverlay').addClass('open');
									} break;

									case 'mobile' : {
										// find node
										let toby = $( '#person-' + personToby.person.slug );
										toby.toggleClass('open');
										window.scrollTo(0, (toby.position().top );
									} break;
								}
							}

						}
        		///

        		// build list of portratit / full images to queueu for preload
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
												imageBin.append(newImage);
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
        		while ( n < people.length ) {
        			let thisPerson = people[n];

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

	// --------------------------------------------------------------------------------------------------------
	// people bio data AJAX load END
	// --------------------------------------------------------------------------------------------------------

    // close desktop overlay
		$('.bioOverlay .white').on('click',function(e){
			$('.bioOverlay').removeClass('open');
		});

		// handle desktop paging
		$('.paging li').on('click',function(e){
			e.preventDefault();

			let action = $(this).attr('class');
			let newPerson = -1;

			switch( action ) {
				case 'previous' : {
					newPerson = currentPerson - 1;
					if ( newPerson < 0 ) {
						newPerson = peopleCount - 1;
					}
				} break;
				case 'next' : {
					newPerson = currentPerson + 1;
					if ( newPerson === peopleCount ) {
						newPerson = 0;
					}

				} break;
			}

			currentPerson = newPerson;
			updateBioOverlay( getPersonBySlug( people[currentPerson].slug ) );
		});

		if( !$('.people').hasClass('peopleSlickSlider') ){
			// only apply onclick to items on 'People' page, not on home page people carousel
			// open / update overlay when user clicks on portrait
			$('.people .item').on('click',function(e){
				e.preventDefault();

				personClick($(this));
			});
		}
	}

	// apply animations on all other pages
	if ($('main.homePage').length === 0) {
		thisPageAnimation.apply();
	}


	// set up people slickslider and set up animations AFTER it has been initialised
	if ($('main.homePage').length > 0) {

		// --------------------------------------------------------------------------------------------------------
		// home page People carousel START
		// --------------------------------------------------------------------------------------------------------

		// people slickslide carousel 4 x 2 grid
		// https://kenwheeler.github.io/slick/
		// https://stackoverflow.com/questions/33800622/slick-carousel-in-two-rows-left-to-right
		// multiple row item flow order is odd
		// 1 3 5 7
		// 2 4 6 8
		// changing the numbers of rows displayed based on responsive breakpoints *does not work* in slickslider
		// got for single row across all for lump for 2 x 1 items in mobile view

		/*
		clever trick to show part of next (righthand) slide on screen
		https://stackoverflow.com/questions/41905363/show-half-of-next-slide-without-center-mode-in-slick-slider

		.slick-list{
			padding:0 20% 0 0 !important;
		}
		*/

		// hide slickslider until it has been initiliased
		if ( $('.people').length > 0 && $('.people').hasClass('peopleSlickSlider')) {
			
			// $('.ssp').on('reInit', function(event, slick, direction){});
			
			// just to be useful, when slickslider resizes the elments in carousel when the width changes (breakpoints), it creates new DOM nodes, stripping off any existing DOM events
			// rebind events onto new DOM nodes
			$('.ssp').on('breakpoint', function(event, slick, direction){

				/*
			  // $('.people .item').off('click');
			  $('.people .item').on('click',function(e){
					e.preventDefault();
					personClick($(this));
				});
				*/
			});

			$('.ssp').on('init', function(event, slick, direction){

				// --------------------------------------------------------------------------------------------------------
				// start of slickslide 'init' event
				// --------------------------------------------------------------------------------------------------------

				// stop transition on child nodes bubbling up to .fwop
				$('.fwop').children().each(function(i){
					$(this).get(0).addEventListener('transitionend', (e) => {
						e.stopPropagation();
					});
				})

				/*
			  // $('.people .item').off('click');
			  $('.people .item').on('click',function(e){
					e.preventDefault();
					personClick($(this));
				});
				*/

				// fwop transition has ended, the slickslider carousel is now visible
				$('.fwop').get(0).addEventListener('transitionend', (e) => {

					/*
				  if ( e.target.className.indexOf('fwop') !== -1 ) {
				  	console.log('.fwop .ready Transition ended');	
				  }
					*/

				  // ---------- ** ----------
				  // GenericScrollHandler -->
				  // ---------- ** ----------

				  // slides items on page load
				  // adjust so that the animation occurs when the people module scolls into view
				  // home page 'People' module carousel slide-in animation 
				  // ADD-TO-ANIMATION_QUEUE
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

						  /*
							let newState = (percent === 1.0 );

					  	if(newState !== instance.state) {
					  		instance.state = newState;
					  		instance.animationComplete = false;

					  		// console.log(instance.state ? "fade in" : "fade out");

							  if ( !this.animateOnce || !instance.animationComplete ) {
								  // stagger items $('.people .item')
									let peopleStaggerAnimation = new QueueSingle({
										"settings" : {
											"delay" : 150 
										},
										"actions" : {
											// run when queue is started
											"queueStart" : function(queue){},

											// run when an item is started
											"itemStart" : function(queue,worker){
												// console.log(worker.index);
												if (worker.index === 0 ) {
													window.requestAnimationFrame(() => {
														worker.node.addClass('ready');
													});
													queue.workerComplete();	
												} else {
													window.setTimeout( () => {
														window.requestAnimationFrame(() => {
															if (instance.state) {
																worker.node.addClass('ready');
															} else {
																if (!instance.animateOnce){
																	worker.node.removeClass('ready');	
																}
															}
														});
														queue.workerComplete();	
													}, queue.settings.delay );
												}
											},

											// run when an item finishes (image.load)
											"itemComplete" : function(queue,worker){},

											// run when all items have finished
											"queueComplete" : function(queue){

												instance.animationComplete = true;

												// ready hidden items in slickslider
												window.requestAnimationFrame(() => {
													if (instance.state) {
														$('.people .item').addClass('ready');
													} else {
														if (!instance.animateOnce){
															$('.people .item').removeClass('ready');	
														}
														
													}
												});
											}
										}
									});
									// queue items which are visible when slickslider starts -> .people .slick-active .item
									$('.people .slick-active .item').each(function(i) {
										peopleStaggerAnimation.addItem({
											"index" : i,
											"node" : $(this)
										});

									});
									peopleStaggerAnimation.startQueue();
							  }
					  	}
					  	*/
						}
					});				  

					// $('.people .item').addClass('ready');
				});
				
				$('.fwop').addClass('ready');

				// slicslideer created, set up animations
				// need to do this after slickslider runs as the top position of elements in the page after the people module change
				thisPageAnimation.apply();

				// --------------------------------------------------------------------------------------------------------
				// end of slickslide 'init' event
				// --------------------------------------------------------------------------------------------------------

			});

			$('.ssp').slick({
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

		// --------------------------------------------------------------------------------------------------------
		// home page People carousel START
		// --------------------------------------------------------------------------------------------------------

	}
