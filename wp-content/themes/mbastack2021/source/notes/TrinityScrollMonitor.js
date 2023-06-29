/* ------------------------------------------------ */
// TrinityScrollMonitor
/* ------------------------------------------------ */

class TrinityScrollMonitor{
	constructor(parameters){
		this.target = parameters.target;
		this.top = parameters.top;
		this.height = parameters.height;
		this.range = parameters.range;

		this.bound = false;
		this.active = false;

		this.init();
	}

	init(){
		let that = this;

		this.vw = $(window).height();
		this.scene = this.target.get(0);

		this.panels = this.target.find('.panel');
		this.controls = this.target.find('.controls li');
		this.jump1 = this.panels.eq(0).find('button.scrolldown');
		this.jump2 = this.panels.eq(1).find('button.scrolldown');

		this.top = this.target.position().top;

		this.a = this.top;
		this.b = this.top + this.range;

		if (!this.bound) {
			$(window).on('scroll',() => { this.monitor(); });

		this.jump1.on('click',function(e){
			e.preventDefault(0);
			that.scrollTo(0.4);
		});
		this.jump2.on('click',function(e){
			e.preventDefault(0);
			that.scrollTo(0.7);
		});

		this.controls.on('click',function(e){
		e.preventDefault(0);
			const offsets = [0.1, 0.4, 0.7];
			let index = that.controls.index($(this));
			// let offset = 0.333 * index;
			let offset = offsets[index];
			that.scrollTo(offset);
		});

		this.bound = true;

		}

		if (!this.active){
			this.active = true;
		}
	}

	monitor(){
		let that = this;

		let percent = 0;

		// update position if this element is preceeded by other elements
		// this.top = this.target.position().top;

		if (this.active) {

			let scrolltop = $(window).scrollTop();
			let effect = 0;

			if ( scrolltop < this.a){
				effect = 0;
				percent = 0.0;
			}
			if ( scrolltop > this.b ){
				effect = this.range;
				percent = 1.0;
			}

			if ( scrolltop > this.a && scrolltop < this.b) {
				effect = scrolltop - this.a;

				// percent
				percent = (scrolltop - this.a) / (this.b - this.a);

				that.index = -1;

				if (0 < percent && percent < 0.333) {
					that.index = 0;
				}
				if (0.333 < percent && percent < 0.666) {
					that.index = 1;
				}
				if (0.666 < percent && percent < 1.0) {
					that.index = 2;
				}
			}

			window.requestAnimationFrame(function(){
				// move the top of the box down to remain in the same place relative to the browser viewport
				that.scene.style.marginTop = effect + 'px';

				if ( percent === 0.0) {
					that.panels.removeClass('active');	
					that.controls.removeClass('active');	
				} else {
					that.panels.eq(that.index).addClass('active');
					that.panels.not( that.panels.eq(that.index) ).removeClass('active');

					that.controls.eq(that.index).addClass('active');
					that.controls.not( that.controls.eq(that.index) ).removeClass('active');
				}
			});
		}
	}

	stopMonitor(){
		this.active = false;
	}

	scrollTo(percent){
		let scrolltoplace = (percent * this.range) + this.top;
		window.scrollTo(0, scrolltoplace );	
	}
}

/*
const pausedModule = $('section.trinity')
const range  = 1000; // 1000px 'pause' in scrolling to accomodate animation
let pauseTop = pausedModule.position().top;
let pauseHeight = pausedModule.height();
let thisScrollMonitor =  TrinityScrollMonitor({
	"target" : pausedModule,
	"top" : pauseTop,
	"height" : pauseHeight,
	"range" : range 
});
*/
