import { ElementHTML } from './ElementHTML'
export class BtnAddCollection extends ElementHTML {
  connectedCallback () {
    this.classList.add('btn-addcollection')
    const title = this.getAttribute('title')
    const iElement = document.createElement('i')
    const spanElement = document.createElement('span')
    spanElement.appendChild(document.createTextNode(title))
    this.append(iElement)
    this.append(spanElement)
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const fieldset = element.currentTarget.closest('fieldset')
    const counter = fieldset.querySelectorAll('.CollectionRow').length
    const html = fieldset.dataset.prototype.replace(/__name__/g, counter)
    const FieldRow = fieldset.querySelector('.FieldRow')
    if (FieldRow !== null) {
      FieldRow.innerHTML += html
    }
  }
}
