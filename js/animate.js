/**
 * Performs an animation
 * eg.		animate({
				duration: 3000,
				timing: function elastic(x, timeFraction) {
					return Math.pow(2, 10 * (timeFraction - 1)) * Math.cos(20 * Math.PI * x / 3 * timeFraction)
				}.bind(null, 1.5),
				draw: function(progress) {
					brick.style.left = progress * 500 + 'px';
				}
			});
 */
function animate({duration, draw, timing}) {

	let start = performance.now();

	requestAnimationFrame(function animate(time) {
		let timeFraction = (time - start) / duration;
		if (timeFraction > 1)
			timeFraction = 1;

		let progress = timing(timeFraction);

		draw(progress);

		if (timeFraction < 1) {
			requestAnimationFrame(animate);
		}

	});
}

/* Timing Functions - alone these functions use easeIn (effect at start) */
/**
 * Power of n
 * eg. x = 2 => a parabolic curve
 * eg. x = 5 => cubic curve
 */
function quad(x, timeFraction) {
	if (!x)
		x = 2;
	return Math.pow(timeFraction, x);
}
/**
 * The arc - part of a sine wave
 */
function circ(timeFraction) {
	return 1 - Math.sin(Math.acos(timeFraction));
}
/**
 * Back: bow shooting - pull the bowstring; and then shoot
 * x is the elasticity coefficient
 * eg. x = 1.5
 */
function back(x, timeFraction) {
	return Math.pow(timeFraction, 2) * ((x + 1) * timeFraction - x);
}
/**
 * Bounce - dropping a ball
 */
function bounce(timeFraction) {
	for (let a = 0, b = 1; 1; a += b, b /= 2) {
		if (timeFraction >= (7 - 4 * a) / 11) {
			return -Math.pow((11 - 6 * a - 11 * timeFraction) / 4, 2) + Math.pow(b, 2);
		}
	}
}
/**
 * Elastic animation
 * x is the initial range
 * eg. x = 1.5
 */
function elastic(x, timeFraction) {
	return Math.pow(2, 10 * (timeFraction - 1)) * Math.cos(20 * Math.PI * x / 3 * timeFraction);
}

/**
 * easeOut - combine an easeIn Timing Function to reverse it
 * eg let bounceEaseOut = makeEaseOut(bounce); - bounce at the end of the animation
 */
function makeEaseOut(timing) {
	return function(timeFraction) {
		return 1 - timing(1 - timeFraction);
	};
}

/**
 * easeInOut - shows the effect both in the beginning and the end of the animation
 * eg. bounceEaseInOut = makeEaseInOut(bounce);	ie. ... timing: bounceEaseOut, ...
 */
function makeEaseInOut(timing) {
	return function(timeFraction) {
		if (timeFraction < .5)
			return timing(2 * timeFraction) / 2;
		else
			return (2 - timing(2 * (1 - timeFraction))) / 2;
	};
}
