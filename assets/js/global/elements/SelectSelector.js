import TomSelect from 'tom-select'
export class SelectSelector extends HTMLSelectElement {
  connectedCallback () {
    const idElement = this.getAttribute('id')
    this.url = this.dataset.url
    console.log(this.url)
    if (this.classList.contains('tomselected') === false) {
      if (undefined !== this.url) {
        this.select = new TomSelect(
          `#${idElement}`,
          {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            load: async (query, callback) => {
              const params = {
                name: query
              }
              const response = await fetch(this.url + '?' + new URLSearchParams(params)).then(response => response.json())
              callback(response.results)
            },
            render: {
              option: (item, escape) => {
                return this.optionItem(item, escape)
              },
              item: (item, escape) => {
                return this.optionItem(item, escape)
              }
            }
          }
        )
      } else {
        this.select = new TomSelect(
          `#${idElement}`,
          {
            sortField: {
              field: 'text',
              direction: 'asc'
            }
          })
      }
    }
  }

  optionItem (item, escape) {
    return `<span>${escape(item.text)}</span>`
  }
}
