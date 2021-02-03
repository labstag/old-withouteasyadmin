export class AttachmentDelete extends HTMLElement {
  constructor () {
    super()
    this.classList.add('attachment-delete')
    this.innerHTML = '<i></i>'
    this.dataset.toggle = 'modal'
    this.dataset.target = '#deleteAttachmentModal'

    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    console.log(element.currentTarget.dataset)
    const url = element.currentTarget.dataset.url
    const token = element.currentTarget.dataset.token
    const redirect = document.location.href
    const btnConfirm = document.querySelector('.confirm-deleteattachment')
    btnConfirm.dataset.url = url
    btnConfirm.dataset.token = token
    btnConfirm.dataset.redirect = redirect
  }
}
