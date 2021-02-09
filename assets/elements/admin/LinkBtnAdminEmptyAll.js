import { LinkBtnAdmin } from './LinkBtnAdmin'

export class LinkBtnAdminEmptyAll extends LinkBtnAdmin {
  constructor () {
    super()
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const url = element.currentTarget.dataset.url
    const token = element.currentTarget.dataset.token
    const redirect = element.currentTarget.dataset.redirect
    const btnConfirm = document.querySelector('.confirm-emptyall')
    btnConfirm.dataset.url = url
    btnConfirm.dataset.token = token
    btnConfirm.dataset.redirect = redirect
  }
}
