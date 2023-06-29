		if ( $('.lst-fuse').length > 0 ) {

			let fuseModule = $('section.fuse');

			let fdtarget = $('.lst-fuse-d');
			let fdsource = fdtarget.attr('data-source');
			let fdAnimationFrames = lottie.loadAnimation({
				container: fdtarget.get(0), 
				renderer: 'svg',
				loop: false,
				autoplay: false,
				path: fdsource 
			});

			let fmtarget = $('.lst-fuse-m');
			let fmsource = fmtarget.attr('data-source');
			let fmAnimationFrames = lottie.loadAnimation({
				container: fmtarget.get(0), 
				renderer: 'svg',
				loop: false,
				autoplay: false,
				path: fmsource 
			});

			// fdAnimationFrames.goToAndStop(1, true);			
			// fmAnimationFrames.goToAndStop(1, true);		

			let fuseAnimation = new GenericScrollHandler({
				"trigger" : fuseModule,
				"subject" : $('section.fuse figure.lightthefuse'),
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
					fdAnimationFrames.goToAndStop(percent * (fdAnimationFrames.totalFrames - 1), true);			
					fmAnimationFrames.goToAndStop(percent * (fmAnimationFrames.totalFrames - 1), true);			
				}
			});

		}
