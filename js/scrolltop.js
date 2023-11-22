
/* 
Floating 'Scroll to Top of Page' Link Handler
Version 1.0
November 28, 2015
   Will Bontrager Software LLC
   https://www.willmaster.com/
   Copyright 2015 Will Bontrager Software LLC
This software is provided "AS IS," without any warranty of any kind.
*/

// Customization section is next two lines.
var ClickToScrollUpDivID = "click-to-scroll-up";
var ProvideScrollUpLinkPixelDistance = 175;
// End of customization section.

function ScrollUp()
{
   if( document.body && document.body.scrollTop ) { document.body.scrollTop = 0; }
   var scrollpos = GetScrollPosition();
   if( scrollpos > 0 && document.documentElement && document.documentElement.scrollTop ) { document.documentElement.scrollTop = 0; }
   document.getElementById(ClickToScrollUpDivID).style.display = "none";
} // function ScrollUp()

function GetScrollPosition()
{
   var scrollpos = 0;
   if( document.body && document.body.scrollTop ) { scrollpos = document.body.scrollTop; }
   if( scrollpos == 0 && document.documentElement && document.documentElement.scrollTop ) { scrollpos = document.documentElement.scrollTop; }
   return scrollpos;
} // function ScrollTestForImage()

function ScrollTestForImage()
{
   var scrollpos = GetScrollPosition();
   if( scrollpos > ProvideScrollUpLinkPixelDistance ) { document.getElementById(ClickToScrollUpDivID).style.display = "block"; }
   else { document.getElementById(ClickToScrollUpDivID).style.display = "none"; }
} // function ScrollTestForImage()

var addEventHandler = function(element,eventType,functionRef)
{
    if( element == null || typeof(element) == 'undefined' ) { return; }
    if( element.addEventListener ) { element.addEventListener(eventType,functionRef,false); }
    else if( element.attachEvent ) { element.attachEvent("on"+eventType,functionRef); }
    else { element["on"+eventType] = functionRef; }
};

addEventHandler( window, "scroll", ScrollTestForImage );

