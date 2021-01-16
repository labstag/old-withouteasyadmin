require("select2");
export class SelectCountry extends HTMLSelectElement {
  connectedCallback() {
    const id = this.getAttribute("id");
    $("#" + id).select2({theme : "bootstrap4"});
  }
}