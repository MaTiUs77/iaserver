var logo_in;
var logo_out; 
var fxIn = ["flipInX","bounceIn","bounceInDown","bounceInLeft","bounceInRight","bounceInUp","fadeIn","fadeInDown","fadeInDownBig","fadeInLeft","fadeInLeftBig","fadeInRight","fadeInRightBig","fadeInUp","fadeInUpBig","flipInX","flipInY","lightSpeedIn"];
var fxOut = ["fadeOutLeft","bounceOut","bounceOutDown","bounceOutLeft","bounceOutRight","bounceOutUp","fadeOut","fadeOutDown","fadeOutDownBig","fadeOutLeft","fadeOutLeftBig","fadeOutRight","fadeOutRightBig","fadeOutUp","fadeOutUpBig","flipOutX","flipOutY","lightSpeedOut"];
function LogoAnim(x) {
	$('#animationLogo').removeClass().addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		//$(this).removeClass();
	});
};

function TextAnim(x) {
	$('#animationText').removeClass().addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		//$(this).removeClass();
	});
};

function LogoIn() {
	clearInterval(logo_in);
	var newFx = fxIn[Math.floor(Math.random()*fxIn.length)];
	LogoAnim(newFx);
	
	newFx = fxIn[Math.floor(Math.random()*fxIn.length)];
	TextAnim(newFx);
	
	logo_out = setTimeout('LogoOut()',5000);
}

function LogoOut() {
	clearInterval(logo_out);
	var newFx = fxOut[Math.floor(Math.random()*fxOut.length)];
	LogoAnim(newFx);
	
	newFx = fxOut[Math.floor(Math.random()*fxOut.length)];
	TextAnim(newFx);

	logo_in = setTimeout('LogoIn()',1000);
}

$(function(){
    LogoIn();
});
