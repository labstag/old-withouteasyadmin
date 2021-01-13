export class InputVille extends HTMLInputElement
{
  connectedCallback()
  {
    this.setAttribute('autocomplete', 'off');
    const idList = (Date.now().toString(36) + Math.random().toString(36).substr(2, 5)).toUpperCase();
    let datalist = document.createElement('datalist');
    datalist.setAttribute('id', idList);
    this.setAttribute('list', idList);
    this.closest(".form-group").appendChild(datalist);
    const row = this.closest(".row");
    const selects = row.getElementsByTagName('select');
    let select = null;
    selects.forEach(
      (element) => {
        let isValue = element.getAttribute('is');
        if (isValue == 'select-country') {
          select = element;
        }
      }
    );
    if (null == select) {
      return;
    }


    this.country = select;
    this.url = row.dataset.url;
    this.addEventListener('keyup', this.onKeyup);

    this.country = select;
    this.url = row.dataset.url;
    this.addEventListener('keyup', this.onKeyup);
  }

  onKeyup(element) {
    console.log(['ville', this.url, this.country.value, this.value]);
  }
}