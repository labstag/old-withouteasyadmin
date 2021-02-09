export class BtnToggleFieldset extends HTMLElement {
  connectedCallback () {
    this.classList.add('btn-togglefieldset')
    const iElement = document.createElement('i')
    iElement.setAttribute('class', 'min')
    this.append(iElement)
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const iElement = element.currentTarget.querySelector('i')
    const contains = iElement.classList.contains('min')
    iElement.classList.remove('min')
    iElement.classList.remove('max')
    iElement.classList.add(contains ? 'max' : 'min')
    const fieldset = element.currentTarget.closest('fieldset')
    const fieldrow = fieldset.querySelector('.FieldRow')
    const btnCollectionAdd = fieldset.querySelector('.BtnCollectionAdd')
    if (btnCollectionAdd != null) {
      if (
        btnCollectionAdd.style.display === '' ||
        btnCollectionAdd.style.display === 'block'
      ) {
        btnCollectionAdd.style.display = 'none'
      } else {
        btnCollectionAdd.style.display = 'block'
      }
    }
    if (fieldrow.style.display === '' || fieldrow.style.display === 'block') {
      fieldrow.style.display = 'none'
    } else {
      fieldrow.style.display = 'block'
    }
  }
}
