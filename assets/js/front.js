import "./../scss/front.scss";
import "./global";
const gotop = document.querySelector("#footer-gotop");
function gotopClick() {
  window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
}
if (gotop !== null) {
  gotop.addEventListener("click", gotopClick);
}
