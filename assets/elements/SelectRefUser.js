require("select2");
export class SelectRefUser extends HTMLSelectElement
{
  connectedCallback()
  {
    console.log('select user');
    const id = this.getAttribute("id");
    $("#" + id).select2({
      theme: "bootstrap4",
      ajax: {
        url: this.dataset.url,
        data: function (params)
        {
          let query = {
            name: params.term
          };

          return query;
        },
        dataType: 'json'
      }
    });


  }
}