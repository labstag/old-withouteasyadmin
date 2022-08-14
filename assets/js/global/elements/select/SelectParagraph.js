import TomSelect from 'tom-select'
export class SelectParagraph extends HTMLSelectElement {
  connectedCallback () {
    const idElement = this.getAttribute('id')
    this.url = this.getAttribute('data-url')
    this.select = new TomSelect(`#${idElement}`)
    this.select.on('change', element => { this.onChange(element) })
  }

  fetchResponse (response) {
    console.log('response', response)
  }

  async onChange (element) {
    if (element !== '') {
      const params = {
        data: element
      }

      const response = await fetch(this.url + '?' + new URLSearchParams(params))
      this.fetchResponse(response)
      this.select.clear()
    }
  }
}
