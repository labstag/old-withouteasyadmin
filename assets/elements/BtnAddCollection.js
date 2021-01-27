export class BtnAddCollection extends HTMLElement {
  connectedCallback () {
    this.classList.add('btn-addcollection')
    const title = this.getAttribute('title')
    this.innerHTML = `<i></i><span>${title}</span>`
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const fieldset = element.currentTarget.closest('fieldset')
    const counter = fieldset.querySelectorAll('.CollectionRow').length
    const html = fieldset.dataset.prototype.replace(/__name__/g, counter)
    const FieldRow = fieldset.querySelector('.FieldRow')
    FieldRow.innerHTML = FieldRow.innerHTML + html
  }
}
