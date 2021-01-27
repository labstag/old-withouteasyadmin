export class ModalConfirmWorkflow extends HTMLButtonElement {
  constructor () {
    super()
    this.classList.add('confirm-workflow')
    this.addEventListener('click', this.onClick)
  }

  onClick (event) {
    event.preventDefault()
    const element = event.currentTarget
    const url = element.dataset.url
    const token = element.dataset.token
    const redirect = element.dataset.redirect
    const urlSearchParams = new URLSearchParams()
    urlSearchParams.append('_token', token)
    const options = {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: urlSearchParams
    }
    fetch(url, options)
      .then((response) => {
        window.location.href = redirect
      })
      .catch((err) => {
        console.log(err)
      })
  }
}
