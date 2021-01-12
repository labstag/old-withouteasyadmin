export class InputPhone extends HTMLInputElement{
  connectedCallback()
  {
    console.log('phone');
    const row = this.closest(".row");
    const url = row.dataset.url;
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
    
    console.log([url, select.value, this.value]);
  }
}