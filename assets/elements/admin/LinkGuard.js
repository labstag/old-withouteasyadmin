export class LinkGuard extends HTMLElement {
  constructor () {
    super()
    const title = this.dataset.title
    const href = this.dataset.href
    this.aElement = document.createElement('a')
    this.iElement = document.createElement('i')
    this.spanElement = document.createElement('span')
    this.aElement.classList.add('link-guard')
    this.aElement.setAttribute('href', href)
    this.iElement.setAttribute('title', title)
    this.spanElement.innerHTML = title

    this.aElement.append(this.iElement)
    this.appendChild(this.aElement)
  }
}
