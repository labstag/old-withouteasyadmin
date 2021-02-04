export class AttachmentImg extends HTMLElement {
  constructor () {
    super()
    const title = this.dataset.name
    this.classList.add('attachment-img')
    if (this.dataset.url === '#') {
      this.innerHTML = `<fieldset><legend>${title}</legend><img /></fieldset>`
    } else {
      this.innerHTML = `<fieldset><legend>${title}</legend><img /><br /><attachment-delete></attachment-delete></fieldset>`
    }

    const imgs = this.getElementsByTagName('img')
    const img = imgs[imgs.length - 1]

    img.setAttribute('src', this.dataset.src)
    if (this.dataset.url !== '#') {
      const btnDeletes = this.getElementsByTagName('attachment-delete')
      const btnDelete = btnDeletes[btnDeletes.length - 1]
      btnDelete.dataset.token = this.dataset.token
      btnDelete.dataset.url = this.dataset.url
    }
  }
}
