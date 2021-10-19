import { ElementHTML } from './../../../global/elements/ElementHTML'
import { AttachmentDelete } from './AttachmentDelete'
export class AttachmentImg extends ElementHTML {
  constructor () {
    super()
    const title = this.getAttribute('name')
    this.classList.add('attachment-img')
    const fieldsetElement = document.createElement('fieldset')
    const legendElement = document.createElement('legend')
    legendElement.append(document.createTextNode(title))
    fieldsetElement.append(legendElement)
    const pictureElement = document.createElement('picture')
    const imgElement = document.createElement('img')
    pictureElement.append(imgElement)
    fieldsetElement.append(pictureElement)
    if (this.getAttribute('url') !== '#') {
      const brElement = document.createElement('br')
      fieldsetElement.append(brElement)
      const divElement = document.createElement('div')
      divElement.classList.add('text-center')
      const attachmentDeleteElement = new AttachmentDelete()
      divElement.append(attachmentDeleteElement)
      fieldsetElement.append(divElement)
    }

    this.append(fieldsetElement)

    const imgs = this.getElementsByTagName('img')
    const img = imgs[imgs.length - 1]

    img.setAttribute('src', this.getAttribute('src'))
    if (this.getAttribute('url') !== '#') {
      const btnDeletes = this.getElementsByTagName('attachment-delete')
      const btnDelete = btnDeletes[btnDeletes.length - 1]
      btnDelete.setAttribute('token', this.getAttribute('token'))
      btnDelete.setAttribute('url', this.getAttribute('url'))
    }
  }
}
