// JavaScript som förhindrar användaren att skriva in bokstäver i
// "year" inputen. Förhindrar också användaren att skriva in mer
// än 4 siffror.

function validateYear(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === "paste") {
    key = event.clipboardData.getData("text/plain");
  } else {
    // Handle key press
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
  }
  var regex = /^[0-9]*$/;
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}

document.querySelector(".validate-year").maxLength = "4";
