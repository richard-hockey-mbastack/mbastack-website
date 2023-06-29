const ScrollLottie = (obj) => {

  let anim = lottie.loadAnimation({
    container: document.querySelector(obj.target), 
    renderer: 'svg',
    loop: false,
    autoplay: false,
    path: obj.path 
  });

  let timeObj = {currentFrame: 0}

  // control speed of animation by adjusting height of track the pinned element sits in -> slower animation, taller track
  let endString = (obj.speed === "slow") ? "+=2000" : (obj.speed === "medium") ? "+=1000" : (obj.speed === undefined) ? "+=1250" : "+=500";

  ScrollTrigger.create({
    trigger: obj.target,
    scrub: true,
    pin: true,
    start: "top top",
    end: endString, 
    onUpdate: self => {

      if(obj.duration) {
        gsap.to(timeObj, {
          duration: obj.duration,
          currentFrame:(Math.floor(self.progress *  (anim.totalFrames - 1))),
          onUpdate: () => {

            // step through animation frames based on scroll position
            anim.goToAndStop(timeObj.currentFrame, true)
          },
          ease: 'expo'
        })
      } else {

        // go to final frame of animation
        anim.goToAndStop(self.progress *  (anim.totalFrames - 1), true)
      }
    }
  });  

}

/*
<div id="animationWindow"></div>

ScrollLottie({
  target: '#animationWindow',
  path: 'https://assets10.lottiefiles.com/packages/lf20_ohzux8th/data.json', 
  duration: 2, 
  speed: 'slow' // governs height of scroll track
})
*/