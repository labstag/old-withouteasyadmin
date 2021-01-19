export class LinkDestroy extends HTMLElement {
  constructor() {
    super();
    if ("" != this.innerHTML) {
      return;
    }

    let title = this.dataset.title;
    let href = this.dataset.url;
    this.classList.add("link-destroy");
    this.dataset.toggle = "modal";
    this.dataset.target = "#destroyModal";
    this.innerHTML = `<i title="${title}"></i><span>${title}</span>`;
    this.addEventListener("click", this.onClick);
  }

  onClick(element) {
    element.preventDefault();
    let url = element.currentTarget.dataset.url;
    let token = element.currentTarget.dataset.token;
    let redirect = element.currentTarget.dataset.redirect;
    let btnConfirm = document.querySelector(".confirm-destroy");
    btnConfirm.dataset.url = url;
    btnConfirm.dataset.token = token;
    btnConfirm.dataset.redirect = redirect;
  }
}
