		// people slickslide carousel 4 x 2 grid
		// https://kenwheeler.github.io/slick/
		// https://stackoverflow.com/questions/33800622/slick-carousel-in-two-rows-left-to-right
		// multiple row item flow order is odd
		// 1 3 5 7
		// 2 4 6 8
		// changing the numbers of rows displayed based on responsive breakpoints *does not work* in slickslider
		// got for single row across all for lump for 2 x 1 items in mobile view

		let setPeopleSlider = function(mode) {
			console.log("setPeopleSlider mode:%s", mode);

			$('.ssp').on('init', function(event, slick, direction){
				// console.log('ssp ready');
				$('.fwop').addClass('ready');
			});

			switch(mode) {
				case 1 : {
					currentMode = 1;
					$('.ssp').slick({
						"dots" : false,
						"arrows" : false,
						"infinite" : true,
						"slidesToShow" : 1,
						"slidesToScroll" : 1
					});
				} break;

				case 8 : {
					currentMode = 8;
					$('.ssp').slick({
						"dots" : true,
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
							}
						 ]
					});
				} break;
			}

		};

		let currentMode = 8; // desktop -> 98, mobile = 1
		let newMode = 0;

		if ( $('.people').length > 0 && $('.people').hasClass('peopleSlickSlider')) {
			/// reset scrollTrigger target positions once slickslider has finished rendering to get correct scrollTrigger target offsets
			$('.ssp').on('init',function(event, slick) {
				console.log("ssp init");
				window.setTimeout(function(){
					console.log('ssp refresh');
					ScrollTrigger.refresh();
				},150);
			});
			
			$('.ssp').on('destroy',function(event, slick) {
				console.log('ssp destroy');

				// init new slider based on mode

				window.setTimeout(function(){
					console.log("alpha: 5s", newMode);
					setPeopleSlider(newMode);
				},1000);

				// setPeopleSlider(newMode);
			});

			$(window).on('resize',function(e){
				windowDimensions();
				console.log(windowSize.w);
				newMode = ( windowSize.w > 768) ? 8 : 1;

				if (newMode !== currentMode) {
					console.log("reset people slickslider: %s", newMode);

					// hide current slickslider container
					// $('.people .fwop').removeClass('ready');

					// destroy current slickslider
					$('.ssp').slick('unslick');

					// init new slider based on mode
					// runs on slick 'destroy' event
					// setPeopleSlider(newMode);
				}
			});
	  
			/*
			clever trick to show part of next (righthand) slide on screen
			https://stackoverflow.com/questions/41905363/show-half-of-next-slide-without-center-mode-in-slick-slider

			.slick-list{
				padding:0 20% 0 0 !important;
			}
			*/

			// declare slick 'init' event BEFORE you call slick()

			if (windowSize.w > 768 ) {
				// desktop -> 8 x 2 grid
				setPeopleSlider(8);
			} else {
				// mobile -> single person
				setPeopleSlider(1);
			}

		}
