import { ElementHTML } from './ElementHTML'
export class BtnDelete extends ElementHTML {
  connectedCallback () {
    if (this.innerHTML.trim() === '') {
      this.append(document.createElement('i'))
    }
    this.classList.add('btn-delete')
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const CollectionRow = element.currentTarget.closest('.CollectionRow')
    CollectionRow.parentNode.removeChild(CollectionRow)
  }
}
