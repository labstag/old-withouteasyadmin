export class InputCodePostal extends HTMLInputElement
{
  connectedCallback()
  {
    const row = this.closest(".row");
    this.setAttribute('autocomplete', 'off');
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
  }

  onKeyup(element) {
    console.log(['codepostal', this.url, this.country.value, this.value]);
  }
}