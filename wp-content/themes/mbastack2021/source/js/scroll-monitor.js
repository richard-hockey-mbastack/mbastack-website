		/*
		// regex via class.matches(types)
		// let types = /(homeBanner|twoColumnMixed|sharedCaseStudies|latestClients|latest-news-home|people|homeValues)/;

		// let matches = $(this).attr('class').match(types);
		// if ( matches )
		// 	moduleType = matches[0];

		let modules = [];
		// list module types (clasees) for home page
		let types = [
			{
				"id" : "homeBanner",
				"x" : ""
			},
			{
				"id" : "twoColumnMixed",
				"x" : ""
			},
			{
				"id" : "sharedCaseStudies",
				"x" : ""
			},
			{
				"id" : "latestClients",
				"x" : ""
			},
			{
				"id" : "latest-news-home",
				"x" : ""
			},
			{
				"id" : "people",
				"x" : ""
			},
			{
				"id" : "homeValues",
				"x" : ""
			},
			{
				"id" : "pageBanner",
				"x" : ""
			},
			{
				"id" : "singleColumn",
				"x" : ""
			},
			{
				"id" : "group",
				"x" : ""
			}
		];

		// first pass, get top offset for each module
		$('.module').each(function(i){
			let moduleClasses = $(this).attr('class');
			let moduleID = $(this).attr('id');
			// determine module type
			let moduleType = '';
			moduleType = types.find(t => {return moduleClasses.indexOf(t.id) > -1} );

			let start = $(this).position().top;
			let h = $(this).height();
			let h2 = Math.floor( h / 2 );

			if ( moduleType.id !== 'sharedCaseStudies') {
				modules.push({
					"index" : i,
					"type" : moduleType.id,
					"identifier" : moduleID,
					"start" : start,
					"end" : start + h,
					"h" : h,
					"halh" : h2,
					"median" : start + h2
				});
			}

			// handle sharedCaseStudies as special case
			// break up into div.caseStudy id=casestudy-704

			if ( moduleType.id === 'sharedCaseStudies') {
				let moduleType = 'caseStudy';
				let topOffset = start;
				$(this).find('.caseStudy').each(function(i){	
					let moduleID = $(this).attr('id');
					let start = $(this).position().top + topOffset;
					let h = $(this).height();
					let h2 = Math.floor( h / 2 );
					modules.push({
						"index" : i,
						"type" : moduleType,
						"identifier" : moduleID,
						"start" : start,
						"end" : start + h,
						"h" : h,
						"halh" : h2,
						"median" : start + h2
					});
				});

			}
		})
		
		// console.log(modules);

		// modules are visible/not visible on page load

		let HandleVisibility = function(thisModule) {

			return true;

			let viewportTop = $(window).scrollTop();
			let viewportBottom = viewportTop + windowSize.h;

			let mtop = thisModule.start;
			let mbottom = thisModule.end;

			// console.log("wt: %s wb: %s ms: %s me: %s id: '%s'",viewportTop,viewportBottom,mtop,mbottom, thisModule.identifier);

			let startVisisble = (viewportTop <= mtop) && (mtop <= viewportBottom);
			let endVisible = (viewportTop <= mbottom) && (mbottom <= viewportBottom);
			let startAboveViewport = (viewportTop >= mtop);
			let endBelowViewport = (viewportBottom <= mbottom);
			let viewPortLiesEWithinModule = startAboveViewport && endBelowViewport;

			// viewport lies entirely within module
			if ( viewPortLiesEWithinModule ) {
				$('#' + thisModule.identifier).removeClass('hidden');
			}

			// start lies within viewport or end lies within viewport
			if( startVisisble || endVisible ) {
				$('#' + thisModule.identifier).removeClass('hidden');
			}

			// not visible
			if( !startVisisble && !endVisible && !viewPortLiesEWithinModule) {
				$('#' + thisModule.identifier).addClass('hidden');
			}

		};

		n = 0;
		while( n < modules.length) {
			let thisModule = modules[n];
			HandleVisibility(thisModule);
			n++;
		}

		// respond to windows scroll
		$(window).scroll(function(){
			let throttle = window.setTimeout(function(){
				// let currentScrollTop = $(window).scrollTop();			
				// console.log(currentScrollTop);

				n = 0;
				while( n < modules.length) {
					let thisModule = modules[n];
					HandleVisibility(thisModule);
					n++;
				}

			}, 10);
		});
		*/
