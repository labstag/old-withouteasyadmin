export class LinkShow extends HTMLElement
{
  constructor() {
    super();
    if ('' != this.innerHTML) {
      return;
    }

    let title = this.dataset.title;
    let href = this.dataset.href;
    this.aElement = document.createElement('a');
    this.iElement = document.createElement('i');
    this.spanElement = document.createElement('span');
    this.aElement.classList.add('link-show');
    this.aElement.setAttribute('href', href);
    this.iElement.setAttribute('title', title);
    this.spanElement.innerHTML = title

    this.aElement.append(this.iElement);
    this.appendChild(this.aElement);
  }
}