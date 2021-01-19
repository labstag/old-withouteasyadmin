import { LinkBtnAdmin } from "./LinkBtnAdmin";

export class LinkBtnAdminDestroy extends LinkBtnAdmin{
  constructor() {
    super();
    this.addEventListener('click', this.onClick);
  }

  onClick(element)
  {
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