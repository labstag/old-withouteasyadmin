export class TableDatatable extends HTMLTableElement {
  constructor () {
    super()
    const ths = this.getElementsByTagName('th')
    const tbodys = this.getElementsByTagName('tbody')
    const thLast = ths[ths.length - 1]
    const tbody = tbodys[tbodys.length - 1]
    thLast.classList.add('thactions')
    const tr = tbody.getElementsByTagName('tr')
    tr.forEach(
      (element) => {
        const tds = element.getElementsByTagName('td')
        const tdLast = tds[tds.length - 1]
        tdLast.classList.add('text-center')
      }
    )
  }
}
