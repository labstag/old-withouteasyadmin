import './global/elements/index'
require('bootstrap')
require('ckeditor4')
window.addEventListener(
  'load',
  event => {
    document.querySelectorAll('.wysiwyg').forEach(element => {
      const idinput = element.getAttribute('id')

      if (global.CKEDITOR.instances[idinput]) {
        global.CKEDITOR.instances[idinput].destroy(true)
        delete global.CKEDITOR.instances[idinput]
      }

      global.CKEDITOR.replace(idinput)
    })
  }
)
