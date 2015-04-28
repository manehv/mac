
// Define the variables
var easing, e, pos;

$(function(){
  
  // Get the click event
  $(".go-top-sets li").on("click", function(){
    
    // Get the class
    easing= $(this).attr("class");

    // Get the scroll pos
    pos= $(window).scrollTop();
    
    // Set the body top margin
    $("body").css({
      "margin-top": -pos+"px",
      "overflow-y": "scroll", // This property is posed for fix the blink of the window width change 
    });

    // Make the scroll handle on the position 0
    $(window).scrollTop(0);
    
    // Get the easing
    switch(easing){
      case "easing-1":
        e= "cubic-bezier(0.600, 0.040, 0.980, 0.335)";
        break;
      case "easing-2":
        e= "cubic-bezier(1.000, -0.560, 0.000, 1.455)";
        break;
      case "easing-3":
        e= "cubic-bezier(0.175, 0.885, 0.320, 1.275)";
        break;
    }

    // Add the transition property to the body element
    $("body").css("transition", "all 1s "+e);
    
    // Apply the scroll effects
    $("body").css("margin-top", "0");

    // Wait until the transition end
    $("body").on("webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd", function(){
      // Remove the transition property
      $("body").css("transition", "none");
    });
  });

});