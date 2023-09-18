function loadGlobal () {
  document.querySelectorAll('.wysiwyg').forEach(element => {
    // const idinput = element.getAttribute('id')

    // if (global.CKEDITOR.instances[idinput]) {
    //   global.CKEDITOR.instances[idinput].destroy(true)
    //   delete global.CKEDITOR.instances[idinput]
    // }

    // global.CKEDITOR.replace(idinput)

    global.ClassicEditor.create(element).catch(error => { console.error(error) })
  })
}
window.addEventListener('load', () => loadGlobal())
