import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
export class Wysiwyg extends HTMLTextAreaElement {
  async connectedCallback () {
    try {
      const editor = await ClassicEditor.create(
        this,
        {
          toolbar: {
            items: [
              'heading',
              '|',
              'bold',
              'italic',
              'link',
              'bulletedList',
              'numberedList',
              '|',
              'outdent',
              'indent',
              '|',
              'imageUpload',
              'blockQuote',
              'insertTable',
              'mediaEmbed',
              'undo',
              'redo',
              '|',
              'textPartLanguage',
              'underline',
              '-',
              'removeFormat',
              'code',
              'alignment',
              'codeBlock',
              'findAndReplace',
              '|',
              'fontBackgroundColor',
              'fontColor',
              'fontFamily',
              'highlight',
              'fontSize',
              '|',
              'horizontalLine',
              'htmlEmbed',
              'pageBreak',
              'specialCharacters',
              'strikethrough',
              'restrictedEditingException',
              'subscript'
            ],
            shouldNotGroupWhenFull: true
          },
          language: 'fr',
          image: {
            toolbar: [
              'imageTextAlternative',
              'imageStyle:inline',
              'imageStyle:block',
              'imageStyle:side',
              'linkImage'
            ]
          },
          table: {
            contentToolbar: [
              'tableColumn',
              'tableRow',
              'mergeTableCells',
              'tableCellProperties',
              'tableProperties'
            ]
          },
          licenseKey: '',
        }
      )
      window.editor = editor
    } catch (error) {
      console.error('There was a problem initializing the editor.', error)
    }
  }
}
