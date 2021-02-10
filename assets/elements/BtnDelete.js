export class BtnDelete extends HTMLElement {
  connectedCallback () {
    this.classList.add('btn-delete')
    this.append(document.createElement('i'))
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const CollectionRow = element.currentTarget.closest('.CollectionRow')
    CollectionRow.parentNode.removeChild(CollectionRow)
  }
}
