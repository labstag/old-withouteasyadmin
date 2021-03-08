import TomSelect from 'tom-select'
export class SelectSelector extends HTMLSelectElement {
  connectedCallback () {
    const idElement = this.getAttribute('id')
    new TomSelect(`#${idElement}`)
  }
}
