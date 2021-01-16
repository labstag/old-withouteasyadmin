export class ModalConfirmDelete extends HTMLButtonElement {
  constructor() {
    super();
    this.classList.add('confirm-delete');
    this.addEventListener('click', this.onClick);
  }

  onClick(event) {
    event.preventDefault();
    const element = event.currentTarget;
    const url = element.dataset.url;
    const token = element.dataset.token;
    const redirect = element.dataset.redirect;
    const data = {_token : token};
    const searchParams = Object.keys(data)
                             .map((key) => {
                               return encodeURIComponent(key) + "=" +
                                      encodeURIComponent(data[key]);
                             })
                             .join("&");
    let options = {
      method : "POST",
      headers : {
        "Content-type" : "application/x-www-form-urlencoded; charset=UTF-8",
      },
      body : searchParams,
    };
    fetch(url, options)
        .then((response) => { window.location.href = redirect; })
        .catch((err) => { console.log(err); });
  }
}
