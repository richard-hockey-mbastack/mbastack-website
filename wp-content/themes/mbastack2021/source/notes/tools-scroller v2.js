			/*
			ToolsIcon -> 
			*/

			// .lst-tools1-d, .lst-tools1-m
			// .lst-tools2-d, .lst-tools2-m
			// .lst-tools3-d, .lst-tools3-m
			// .lst-tools4-d, .lst-tools4-m
			// .lst-tools5-d, .lst-tools5-m

			/*
			let toolsIcons = [];
			let toolsModule = $('section.tools');
			$('.lst-tools').each(function(i){
				toolsIcons.push(new ToolsIcon({
					"module" : toolsModule,
					"target" : $(this),
					"index" : i
				}));
			});
			*/
			// scroll controlled icon animations
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

					/*
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.top + "px", "background-color" : "green"}) );
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.bottom + "px", "background-color" : "red"}) );

					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.start + "px", "background-color" : "green", "height" : "1px"}) );
					$('body').append($('<div></div>').addClass('mark').css({"top" : instance.end + "px", "background-color" : "red", "height" : "1px"}) );
					*/
				},
				"monitor" : (instance,scrolltop) => {
					let percent = 0;

					if ( scrolltop < instance.start ) {
						// first frame
						instance.update(0.0);
					}

					if ( scrolltop > instance.end ) {
						// last frame
						instance.update(1.0);
					}

					if ( scrolltop > instance.start && scrolltop < instance.end ) {

						let percent =  (scrolltop - instance.start ) / ( instance.end - instance.start );
						instance.state = true;
						instance.update(percent);	
					}

				},
				"update" : (instance,percent) => {
					// 0.0 > percent > 1.0

					let slider = $('.tools .items');
					let progress = $('.toolsProgress li');

					// switch panels
					if (percent > 0.0 && percent < 0.2) {
						// 1
						instance.current = 0;
						slider.css({"left" : "0%"});
						progress.eq(0).addClass('active');
						progress.slice(1,5).removeClass('active');

						// 0 - 0.2
						// let iconPercent = percent * 5
						// toolsIcons[0].update(iconPercent);
					}
					if (percent > 0.2 && percent < 0.4) {
						// 2
						instance.current = 1;
						slider.css({"left" : "-100%"});
						progress.slice(0,2).addClass('active');
						progress.slice(2,5).removeClass('active');

						// 0.2 - 0.4
						// let iconPercent = (percent - 0.2) * 5;
						// toolsIcons[1].update(iconPercent);
					}
					if (percent > 0.4 && percent < 0.6) {
						// 3
						instance.current = 2;
						slider.css({"left" : "-200%"});
						progress.slice(0,3).addClass('active');
						progress.slice(3,5).removeClass('active');

						// 0.4 - 0.6
						// let iconPercent = (percent - 0.4) * 5;
						// toolsIcons[2].update(iconPercent);
					}
					if (percent > 0.6 && percent < 0.8) {
						// 4
						instance.current = 3;
						slider.css({"left" : "-300%"});
						progress.slice(0,4).addClass('active');
						progress.slice(4,5).removeClass('active');

						// 0.6 - 0.8
						// let iconPercent = (percent - 0.6) * 5;
						// toolsIcons[3].update(iconPercent);
					}
					if (percent > 0.8 && percent < 1.0) {
						// 5
						instance.current = 4;
						slider.css({"left" : "-400%"});
						progress.addClass('active');

						// 0.8 - 1.0
						// let iconPercent = (percent - 0.8) * 5;
						// toolsIcons[4].update(iconPercent);
					}

					if (percent > 0.8) {
						$('.toolsProgress').addClass('end');
					} else {
						$('.toolsProgress').removeClass('end');
					}
				}
			});

			$('.nextItem').on('click',function(e){
				toolsAnimation.current++;

				/*
				slight offset 0.21 instead of 0.2 (20%) to move past scroll point
				0 0.0
				1 0.21
				2 0.42
				3 0.63
				4 0.84
				*/
				let of = ( (toolsAnimation.end - toolsAnimation.start) * (toolsAnimation.current * 0.21) ) + toolsAnimation.start;
				window.scrollTo(0, of );
			});

			$('.toolsProgress li').on('click',function(e){
				let panel = $('.toolsProgress li').index($(this));

				if (panel !== toolsAnimation.current) {
					toolsAnimation.current = panel;
					let of = ( (toolsAnimation.end - toolsAnimation.start) * (toolsAnimation.current * 0.21) ) + toolsAnimation.start;
					window.scrollTo(0, of );
				}
			});
