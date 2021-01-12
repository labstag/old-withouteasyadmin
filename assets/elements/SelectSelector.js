require("select2");
export class SelectSelector extends HTMLSelectElement{
  connectedCallback()
  {
    const id = this.getAttribute("id");
    $("#" + id).select2({ theme: "bootstrap4" });
  }
}