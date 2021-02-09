export class LinkEmpty extends HTMLElement {
  constructor () {
    super()
    const title = this.getAttribute('title')
    this.classList.add('link-empty')
    this.dataset.toggle = 'modal'
    this.dataset.target = '#emptyModal'
    this.innerHTML = `<i title="${title}"></i><span>${title}</span>`
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const url = element.currentTarget.dataset.url
    const token = element.currentTarget.dataset.token
    const redirect = element.currentTarget.dataset.redirect
    const btnConfirm = document.querySelector('.confirm-empty')
    btnConfirm.dataset.url = url
    btnConfirm.dataset.token = token
    btnConfirm.dataset.redirect = redirect
  }
}
