export class LinkTrash extends HTMLElement {
  constructor () {
    super()
    const title = this.getAttribute('title')
    const href = this.getAttribute('href')
    this.aElement = document.createElement('a')
    this.iElement = document.createElement('i')
    this.spanElement = document.createElement('span')
    this.aElement.classList.add('link-trash')
    this.aElement.setAttribute('href', href)
    this.iElement.setAttribute('title', title)
    this.spanElement.innerHTML = title

    this.aElement.append(this.iElement)
    this.appendChild(this.aElement)
  }
}
