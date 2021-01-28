export class LinkBtnAdmin extends HTMLElement {
  constructor () {
    super()
    const aElement = document.createElement('a')
    this.classList.add('link-btnadmin')
    const icon = this.getAttribute('icon')
    const text = this.getAttribute('text')
    const idElement = this.getAttribute('id')
    const href = this.getAttribute('href')
    if (idElement != null) {
      aElement.setAttribute('id', idElement)
    }
    if (href != null) {
      aElement.setAttribute('href', href)
    }
    if (icon != null) {
      const iElement = document.createElement('i')
      iElement.classList.add(icon)
      iElement.setAttribute('title', text)
      aElement.append(iElement)
    }

    const spanElement = document.createElement('span')
    spanElement.innerHTML = text
    aElement.append(spanElement)

    this.appendChild(aElement)
  }
}
