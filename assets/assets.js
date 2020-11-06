require('bootstrap');
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
let wysiwygs = document.querySelectorAll('.wysiwyg');
if (wysiwygs.length) {
  wysiwygs.forEach(element => {
    ClassicEditor
    .create(element)
    .then( editor => {
        window.editor = editor;
    } )
    .catch( error => {
        console.error( 'There was a problem initializing the editor.', error );
    } );
  });
}
console.log("aa");
