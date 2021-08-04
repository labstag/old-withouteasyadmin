import './global'
document.getElementById('footer-gotop').addEventListener('click', function () {
  window.scrollTo(
    {
      top: 0,
      left: 0,
      behavior: 'smooth'
    }
  )
})
