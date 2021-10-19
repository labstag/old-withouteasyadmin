import './btn/index'
import './input/index'
import './select/index'
import { TableDatatable } from './TableDatatable'

customElements.define('table-datatable', TableDatatable, { extends: 'table' })
