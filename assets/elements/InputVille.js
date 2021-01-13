export class InputVille extends HTMLInputElement
{
  connectedCallback()
  {

    const idList = (Date.now().toString(36) + Math.random().toString(36).substr(2, 5)).toUpperCase();
    let datalist = document.createElement('datalist');
    datalist.setAttribute('id', idList);
    this.setAttribute('list', idList);
    this.closest(".form-group").appendChild(datalist);

    console.log('ville');
  }
}