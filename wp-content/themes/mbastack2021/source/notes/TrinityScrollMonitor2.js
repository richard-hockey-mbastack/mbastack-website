/* ------------------------------------------------ */
// TrinityScrollMonitor
/* ------------------------------------------------ */

/*
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

		this.top = this.target.position().top;
		this.a = this.top;
		this.b = this.top + this.range;

		this.current = 0;

		// $('body').append($('<div></div>').addClass('mark').css({"top" : this.a + "px", "background-color" : "green"}) );
		// $('body').append($('<div></div>').addClass('mark').css({"top" : this.b + "px", "background-color" : "red"}) );

		this.slider = $('.tools .items');
		this.progress = $('.toolsProgress li');

		this.itemCount = slider.length;
		console.log(itemCount);

		if (!this.bound) {
			$(window).on('scroll',() => { this.monitor(); });
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
			}

			window.requestAnimationFrame(function(){
				// move the top of the box down to remain in the same place relative to the browser viewport
				that.scene.style.marginTop = effect + 'px';

				//let slider = $('.tools .items');
				//let progress = $('.toolsProgress li');

				//let itemCount = slider.length;
				// console.log(itemCount);

				// switch panels
				if (percent > 0.0 && percent < 0.2) {
					// 1
					that.current = 0;
					that.slider.css({"left" : "0%"});
					that.progress.eq(0).addClass('active');
					that.progress.slice(1,5).removeClass('active');

					// 0 - 0.2
					// let iconPercent = percent * 5
					// toolsIcons[0].update(iconPercent);
				}
				if (percent > 0.2 && percent < 0.4) {
					// 2
					that.current = 1;
					that.slider.css({"left" : "-100%"});
					that.progress.slice(0,2).addClass('active');
					that.progress.slice(2,5).removeClass('active');

					// 0.2 - 0.4
					// let iconPercent = (percent - 0.2) * 5;
					// toolsIcons[1].update(iconPercent);
				}
				if (percent > 0.4 && percent < 0.6) {
					// 3
					that.current = 2;
					that.slider.css({"left" : "-200%"});
					that.progress.slice(0,3).addClass('active');
					that.progress.slice(3,5).removeClass('active');

					// 0.4 - 0.6
					// let iconPercent = (percent - 0.4) * 5;
					// toolsIcons[2].update(iconPercent);
				}
				if (percent > 0.6 && percent < 0.8) {
					// 4
					that.current = 3;
					that.slider.css({"left" : "-300%"});
					that.progress.slice(0,4).addClass('active');
					that.progress.slice(4,5).removeClass('active');

					// 0.6 - 0.8
					// let iconPercent = (percent - 0.6) * 5;
					// toolsIcons[3].update(iconPercent);
				}
				if (percent > 0.8 && percent < 1.0) {
					// 5
					that.current = 4;
					that.slider.css({"left" : "-400%"});
					that.progress.addClass('active');

					// 0.8 - 1.0
					// let iconPercent = (percent - 0.8) * 5;
					// toolsIcons[4].update(iconPercent);
				}

				// item 6
				if (percent > 0.8) {
					$('.toolsProgress').addClass('end');
				} else {
					$('.toolsProgress').removeClass('end');
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
*/
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


/* ------------------------------------------------ */
// ToolsIcon
/* ------------------------------------------------ */

class ToolsIcon{
	constructor(parameters){

		this.module = parameters.module;
		this.target = parameters.target;
		this.index = parameters.index;

		this.init();
	}
	init(){
		this.source = this.target.attr('data-source');

		this.animationFrames = lottie.loadAnimation({
			container: this.target.get(0), 
			renderer: 'svg',
			loop: false,
			autoplay: false,
			path: this.source 
		});

		this.animationFrames.goToAndStop(1, true);
	}
	update(percent){
		this.animationFrames.goToAndStop(percent * (this.animationFrames.totalFrames - 1), true);
	}
}

/*
let toolsIcons = [];
let toolsModule = $('section.tools');
$('.lst-tools').each(function(i){
	console.log( $(this).attr('class'),i );
	toolsIcons.push(new ToolsIcon({
		"module" : toolsModule,
		"target" : $(this),
		"index" : i
	}));
});
*/
