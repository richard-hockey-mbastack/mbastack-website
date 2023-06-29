		// function to update overlay with photo + details
		let updateBioOverlay = function( source ) {

			let details = {
				"forename" : source.attr('data-forename'),
				"surname" : source.attr('data-surname'),
				"role" : source.attr('data-role'),
				"bio" : source.find('.bio').clone(),
				"fullPic" : source.attr('data-full'),
				"linkedinLink" : source.attr('data-linkedin'),
				"twitterLink" : source.attr('data-twitter'),
				"instagramLink" : source.attr('data-instagram'),
				"facebookLink" : source.attr('data-facebook')
			}

			$('#bioPhoto').attr({
				"src" : details.fullPic,
				"alt" : details.forename + " " + details.surname + " - " + details.role
			}).on('load',function(e){
			});

			$('#bioName').text(details.forename + " " + details.surname);
			$('#bioRole').text(details.role);
			$('#bioCopy').empty().append(details.bio);
			$('#bioForename').text(details.forename);

			let mbSocial = $('#bioSocial');
			let hasLinkedin = (details.linkedinLink.length > 0);
			let hasTwitter = (details.twitterLink.length > 0);
			let hasInstagram = (details.instagramLink.length > 0);
			let hasFacebook = (details.facebookLink.length > 0);

			let showSocialMediaLinks = ( hasLinkedin || hasTwitter || hasInstagram || hasFacebook );

			mbSocial.empty();
			if ( showSocialMediaLinks ) {

				mbSocial.append($('<h4></h4>').text('Connect with ' + details.forename));

				let smLinks = $('<ul></ul>');

				if ( hasLinkedin ) {
					let listel = $('<li></li>');
					listel.append( $('<a></a>').attr({"href" : details.linkedinLink}).addClass('linkedin').append($('<span></span>').text('linkedin')) );
					smLinks.append( listel );					
				}

				if ( hasTwitter ) {
					let listel = $('<li></li>');
					listel.append( $('<a></a>').attr({"href" : details.twitterLink}).addClass('twitter').append($('<span></span>').text('twitter')) );
					smLinks.append( listel );					
				}

				if ( hasInstagram ) {
					let listel = $('<li></li>');
					listel.append( $('<a></a>').attr({"href" : details.instagramLink}).addClass('instagram').append($('<span></span>').text('instagram')) );
					smLinks.append( listel );					
				}

				/*
				if (hasFacebook) {
					let listel = $('<li></li>');
					listel.append( $('<a></a>').attr({"href" : details.facebookLink}).addClass('facebook').append($('<span></span>').text('facebook')) );
					smLinks.append( listel );					
				}
				*/

				// email
				// phone
				mbSocial.append( smLinks );						
			}
		};

		// index metadata to feed into next /previous controls
		let items = [];
		let currentItem = -1;
		$('.people .item').each(function(i){
			console.log("index: %s", $(this).attr('id') );
			items.push({
				"id" : $(this).attr('id'),
				"name" : $(this).find('h3').text()
			});
		});

		// close bio overlay
		$('.bioOverlay .white').on('click',function(e){
			$('.bioOverlay').removeClass('open');
		});

		// open / update overlay when user clicks on portrait
		$('.people .item').on('click',function(e){
			e.preventDefault();

			console.log('person overlay trigger');

			let thisPersonBlock = $(this);
			let thisItemid = thisPersonBlock.attr('id');
			let thisItemIndex = $('.item').index($(this));
			let thisMetadata = thisPersonBlock.find('.bioMeta');
			currentItem = thisItemIndex;

			updateBioOverlay(thisMetadata)

			$('html, body').stop().animate({"scrollTop" : $('body').offset().top }, 300);
			$('.bioOverlay').addClass('open');
		});

		// add next / previous behaviours
		$('.paging li').on('click',function(e){
			e.preventDefault();

			let action = $(this).attr('class');

			switch( action ){
				case 'previous' : {
					currentItem--;

					if ( currentItem < 0 ) {
						currentItem = (items.length -1);
					}
				} break;
				case 'next' : {
					currentItem++;

					if ( currentItem === items.length ) {
						currentItem = 0;
					}
				} break;
			}

			console.log(items,currentItem);

			// update overlay
			let thisPersonBlock = $('#' + items[currentItem].id );
			let thisMetadata = thisPersonBlock.find('.bioMeta');

			updateBioOverlay(thisMetadata)

		});
