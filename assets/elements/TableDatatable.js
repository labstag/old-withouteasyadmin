export class TableDatatable extends HTMLTableElement {
  constructor () {
    super()
    const action = this.dataset.action
    const multiple = this.dataset.multiple
    const multipleall = this.dataset.multipleall
    const multipleelement = this.dataset.multipleelement
    const ths = this.getElementsByTagName('th')
    const tbodys = this.getElementsByTagName('tbody')
    const tbody = tbodys[tbodys.length - 1]
    const thLast = ths[ths.length - 1]
    if (action !== undefined) {
      const iElement = document.createElement('i')
      iElement.setAttribute('title', action)
      thLast.append(iElement)
      thLast.classList.add('thactions')
      const tr = tbody.getElementsByTagName('tr')
      tr.forEach(
        element => {
          const tds = element.getElementsByTagName('td')
          const tdLast = tds[tds.length - 1]
          tdLast.classList.add('text-center')
        }
      )
    }
    if (multiple !== undefined) {
      const thElement = document.createElement('th')
      const selectAllElement = document.createElement('select-all')
      if (multipleall !== undefined) {
        selectAllElement.setAttribute('title', multipleall)
      }
      thElement.append(selectAllElement)
      thLast.closest('tr').prepend(thElement)
      const tr = tbody.getElementsByTagName('tr')
      tr.forEach(
        (element) => {
          if (element.dataset.id === undefined) {
            return
          }
          const tdElement = document.createElement('td')
          const selectElementElement = document.createElement('select-element')
          if (multipleelement !== undefined) {
            selectElementElement.dataset.title = multipleelement
          }
          selectElementElement.dataset.id = element.dataset.id
          tdElement.append(selectElementElement)
          element.prepend(tdElement)
        }
      )
    }
  }
}
