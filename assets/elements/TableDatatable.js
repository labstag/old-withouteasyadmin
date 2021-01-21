export class TableDatatable extends HTMLTableElement
{
  constructor() {
    super();
    let ths = this.getElementsByTagName('th');
    let tbodys = this.getElementsByTagName('tbody');
    let thLast = ths[ths.length - 1];
    let tbody = tbodys[tbodys.length - 1];
    thLast.classList.add('thactions');
    let tr = tbody.getElementsByTagName('tr');
    tr.forEach(
      (element) => {
        let tds = element.getElementsByTagName('td');
        let tdLast = tds[tds.length - 1];
        tdLast.classList.add('text-center');
      }
    );
  }
}