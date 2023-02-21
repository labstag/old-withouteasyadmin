import './global'
const gotop = document.getElementById('footer-gotop')
function gotopClick () {
  window.scrollTo(
    {
      top: 0,
      left: 0,
      behavior: 'smooth'
    }
  )
}
if (gotop !== undefined) {
  gotop.addEventListener('click', gotopClick)
}
