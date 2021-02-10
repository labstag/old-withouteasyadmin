export class GuardGroup extends HTMLElement {
  constructor () {
    super()
    this.classList.add('guard-group')
    const uuid = this.uuidv4()
    const inputElement = document.createElement('input')
    inputElement.setAttribute('type', 'checkbox')
    inputElement.setAttribute('id', uuid)
    const labelElement = document.createElement('label')
    labelElement.setAttribute('for', uuid)
    // labelElement.append(document.createTextNode('&nbsp;'))
    this.append(inputElement)
    this.append(labelElement)
    const checkboxs = this.getElementsByTagName('input')
    this.checkbox = checkboxs[0]
    this.token = this.dataset.token
    this.checkbox.addEventListener('change', this.onChange.bind(this))
    this.changeState()
    const observer = new MutationObserver(this.mutationObserver.bind(this))
    observer.observe(this, {
      attributes: true
    })
  }

  mutationObserver (mutations) {
    mutations.forEach(this.forEachMutationObserver.bind(this))
  }

  forEachMutationObserver (mutation) {
    if (mutation.type === 'attributes' && mutation.attributeName === 'data-state') {
      this.changeState()
    }
  }

  changeState () {
    this.checkbox.checked = (this.dataset.state === '1')
  }

  onChange (element) {
    element.preventDefault()
    const urlSearchParams = new URLSearchParams()
    urlSearchParams.append('_token', this.dataset.token)
    urlSearchParams.append('state', (this.checkbox.checked === false) ? 0 : 1)
    const options = {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: urlSearchParams
    }
    fetch(this.dataset.url, options).catch((err) => {
      console.log(err)
    })
  }

  uuidv4 () {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
      const r = Math.random() * 16 | 0; const v = c === 'x' ? r : (r & 0x3 | 0x8)
      return v.toString(16)
    })
  }
}
