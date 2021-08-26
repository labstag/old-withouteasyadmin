import { LinkBtnAdmin } from './LinkBtnAdmin'

export class LinkBtnAdminMove extends LinkBtnAdmin {
  constructor () {
    super()
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const url = this.getAttribute('href')
    const urlSearchParams = new URLSearchParams()
    document.getElementsByClassName('menu-move').forEach(
      async function (ul) {
        const position = []
        ul.getElementsByTagName('li').forEach(
          function (li, index) {
            position.push({
              'id': li.dataset.id,
              'position': index
            })
          }
        )
        urlSearchParams.append('position', JSON.stringify(position))
        const options = {
          method: 'POST',
          headers: {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
          },
          body: urlSearchParams
        }
        await fetch(url, options)
      }
    )
  }
}
