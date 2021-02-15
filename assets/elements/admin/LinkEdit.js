export class LinkEdit extends HTMLElement {
  constructor () {
    super()
    const title = this.dataset.title
    const href = this.dataset.href
    this.aElement = document.createElement('a')
    this.iElement = document.createElement('i')
    this.spanElement = document.createElement('span')
    this.aElement.classList.add('link-edit')
    this.aElement.setAttribute('href', href)
    this.iElement.setAttribute('title', title)
    this.spanElement.append(document.createTextNode(title))

    this.aElement.append(this.iElement)
    this.appendChild(this.aElement)
  }
}
