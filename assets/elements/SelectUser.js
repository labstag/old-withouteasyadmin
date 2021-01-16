require("select2");
export class SelectUser extends HTMLSelectElement
{
  connectedCallback()
  {
    console.log('select user');
    const id = this.getAttribute("id");
    $("#" + id).select2({ theme: "bootstrap4" });
  }
}