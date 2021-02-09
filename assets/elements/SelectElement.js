export class SelectElement extends HTMLElement {
  constructor () {
    super()
    const title = this.dataset.title
    const checkbox = document.createElement('input')
    checkbox.setAttribute('type', 'checkbox')
    if (title !== undefined) {
      checkbox.setAttribute('title', title)
    }
    this.append(checkbox)
  }
}
