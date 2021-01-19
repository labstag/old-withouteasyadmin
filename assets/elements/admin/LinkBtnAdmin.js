export class LinkBtnAdmin extends HTMLElement {
  constructor() {
    super();
    if ('' != this.innerHTML) {
      return;
    }
    let aElement = document.createElement('a');
    this.classList.add('link-btnadmin');
    const icon = this.getAttribute('icon');
    const text = this.getAttribute('text');
    const idElement = this.getAttribute('id');
    const href = this.getAttribute('href');
    if (null != idElement) {
      aElement.setAttribute('id', idElement);
    }
    if (null != href) {
      aElement.setAttribute('href', href);
    }
    if (null != icon) {
      let iElement = document.createElement('i');
      iElement.classList.add(icon);
      iElement.setAttribute('title', text);
      aElement.append(iElement);
    }

    let spanElement = document.createElement('span');
    spanElement.innerHTML = text;
    aElement.append(spanElement);

    this.appendChild(aElement);
  }
}