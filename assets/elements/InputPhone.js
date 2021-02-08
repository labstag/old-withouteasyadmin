export class InputPhone extends HTMLInputElement {
  connectedCallback () {
    this.row = this.closest('.row')
    this.setAttribute('autocomplete', 'off')
    const selects = this.row.getElementsByTagName('select')
    let select = null
    selects.forEach(
      (element) => {
        const isValue = element.getAttribute('is')
        if (isValue === 'select-country') {
          select = element
        }
      }
    )
    if (select == null) {
      return
    }

    this.country = select
    this.url = this.row.dataset.url
    this.timeout = null
    this.addEventListener('keydown', this.onKeydown)
  }

  fetchResponse (response) {
    this.classList.remove('is-valid')
    this.classList.remove('is-invalid')
    this.classList.add(response.isvalid ? 'is-valid' : 'is-invalid')
  }

  fetchCatch (err) {
    console.log(err)
  }

  ajax () {
    const params = {
      country: this.country.value,
      phone: this.value
    }
    fetch(this.url + '?' + new URLSearchParams(params))
      .then(response => response.json())
      .then(this.fetchResponse.bind(this))
      .catch(this.fetchCatch)
  }

  onKeydown () {
    clearTimeout(this.timeout)
    this.timeout = setTimeout(this.ajax.bind(this), 500)
  }
}
