		// CXM scroll interaction
		function BuggerScroller(parameters) {
			this.parameters = parameters;

			this.trigger = parameters.trigger;
			this.phases2 = parameters.phases;

			this.init();
		}
		BuggerScroller.prototype = {
			"constructor" : BuggerScroller,
			"template" : function () {var that = this; },

			"init" : function () {
				var that = this;

				this.triggerNode = this.trigger;
				this.currentTarget = '';

				// set :
				// this.top, this.height, this.bottom, this.wh
				this.getDimensions();

				// window resize
				// update :
				// this.top, this.height, this.bottom, this.wh
				$(window).on('resize',() => { this.getDimensions(); });

				// window scroll
				$(window).scroll(() => { this.monitor($(window).scrollTop()); });
			},
			"getDimensions" : function () {
				var that = this;

				this.top = this.triggerNode.position().top;
				this.height = this.triggerNode.height();
				this.bottom = this.top + this.height;
				this.wh = $(window).height();
			},
			"monitor" : function (scrolltop) {
				var that = this;
				/* */
				// case 3 :
				// top of target moves off top of viewport
				// to
				// bottom of target moves onto bottom of viewport
				if ( (scrolltop) < this.top ){
					this.update(0);
				}

				if ( scrolltop > this.top && ((scrolltop + this.wh) < this.bottom) ) {
					let a = this.top;
					let b = this.bottom - this.wh;

					let percent =  (scrolltop - a ) / ( b - a );

					this.update(percent);

				}

				if ( (scrolltop + this.wh) > this.bottom ){
					this.update(1.0);
				}
				/* */
			},
			"update" : function (percent) {
				var that = this;

				let p2 = percent * 100;
				let index = 0;
				while( index < this.phases2.length) {
					let thisPhase = this.phases2[index];
					if (thisPhase.start <= p2 && p2 <= thisPhase.stop) {
						if ( this.currentTarget !== thisPhase.id ) {
							this.currentTarget = thisPhase.id;
							$('#ct').text(this.currentTarget);
							this.activeTarget = $('#' + thisPhase.id);
							$('.cbitem').not(this.activeTarget).css({"opacity" : 0});
						}
						// convert scroll position into opacity
						let range = thisPhase.stop - thisPhase.start;
						let ratio = ( thisPhase.action === 'fadein' ) ? (p2 - thisPhase.start) / range : 1.0 - ((p2 - thisPhase.start) / range);
						this.activeTarget.css({"opacity" : ratio });
					}
					index++;
				}
			}
		}

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
