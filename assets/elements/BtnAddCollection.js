export class BtnAddCollection extends HTMLElement {
  connectedCallback() {
    this.classList.add("btn-addcollection");
    const title = this.getAttribute("title");
    this.innerHTML = `<i></i><span>${title}</span>`;
    this.addEventListener("click", this.onClick);
  }

  onClick(element) {
    element.preventDefault();
    let fieldset = element.currentTarget.closest("fieldset");
    let counter = fieldset.querySelectorAll(".CollectionRow").length;
    let html = fieldset.dataset.prototype.replace(/__name__/g, counter);
    let FieldRow = fieldset.querySelector(".FieldRow");
    FieldRow.innerHTML = FieldRow.innerHTML + html;
  }
}
