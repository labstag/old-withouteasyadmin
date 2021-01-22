require("select2");
export class SelectRefUser extends HTMLSelectElement
{
  connectedCallback()
  {
    console.log('select user');
    const id = this.getAttribute("id");
    $("#" + id).select2({ theme: "bootstrap4" });
  }
}