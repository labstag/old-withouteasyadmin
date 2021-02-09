export class SelectElement extends HTMLElement {
  constructor () {
    super()
    const title = this.dataset.title
    this.dataset.checked = 0
    const checkbox = document.createElement('input')
    checkbox.setAttribute('type', 'checkbox')
    if (title !== undefined) {
      checkbox.setAttribute('title', title)
    }
    this.append(checkbox)
    checkbox.addEventListener('change', this.onChange)
    const observer = new MutationObserver(this.mutationObserver.bind(this))
    observer.observe(this, {
      attributes: true
    })
  }

  mutationObserver (mutations) {
    mutations.forEach(this.forEachMutationObserver.bind(this))
  }

  forEachMutationObserver (mutation) {
    if (mutation.type === 'attributes' && mutation.attributeName === 'data-checked') {
      this.changeChecked()
    }
  }

  changeChecked () {
    const selectElement = document.querySelectorAll("select-element>input[type='checkbox']:checked")
    const linkBtnAdminEmptiesElement = document.querySelector('link-btnadminempties')
    if (linkBtnAdminEmptiesElement !== null) {
      linkBtnAdminEmptiesElement.style.display = (selectElement.length !== 0) ? 'block' : 'none'
    }
  }

  onChange (event) {
    const target = event.currentTarget
    const checked = target.checked
    const selectElement = target.closest('select-element')
    selectElement.dataset.checked = checked ? 1 : 0
  }
}
