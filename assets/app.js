/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

let myTricksButton = document.getElementById("btn-go-to-tricks");
let myTopButton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
  };
  
  function scrollFunction() {
    if (
      document.body.scrollTop > 20 ||
      document.documentElement.scrollTop > 20
    ) {
      myTopButton.style.display = "block";
      myTricksButton.style.display = "none";
    } else {
      myTricksButton.style.display = "block";
      myTopButton.style.display = "none";
    }
  }

myTricksButton.addEventListener("click", goToTricks);

function goToTricks() {
  document.body.scrollTop = 500;
  document.documentElement.scrollTop = 500;
}

// When the user clicks on the button, scroll to the top of the document
myTopButton.addEventListener("click", backToTop);

function backToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
