import {BtnAddCollection} from './BtnAddCollection'
import {BtnDelete} from './BtnDelete'
import {BtnToggleFieldset} from './BtnToggleFieldset'
import {InputCodePostal} from './InputCodePostal'
import {InputEmail} from './InputEmail'
import {InputGps} from './InputGps'
import {InputPhone} from './InputPhone'
import {InputUrl} from './InputUrl'
import {InputVille} from './InputVille'
import {SelectAll} from './SelectAll'
import {SelectCountry} from './SelectCountry'
import {SelectElement} from './SelectElement'
import {SelectRefUser} from './SelectRefUser'
import {SelectSelector} from './SelectSelector'
import {TableDatatable} from './TableDatatable'

customElements.define('btn-addcollection', BtnAddCollection)
customElements.define('btn-delete', BtnDelete)
customElements.define('btn-togglefieldset', BtnToggleFieldset)

customElements.define('select-country', SelectCountry, {extends : 'select'})
customElements.define('select-selector', SelectSelector, {extends : 'select'})
customElements.define('select-refuser', SelectRefUser, {extends : 'select'})

customElements.define('input-phone', InputPhone, {extends : 'input'})
customElements.define('input-email', InputEmail, {extends : 'input'})
customElements.define('input-url', InputUrl, {extends : 'input'})
customElements.define('input-gps', InputGps, {extends : 'input'})
customElements.define('input-codepostal', InputCodePostal, {extends : 'input'})
customElements.define('input-ville', InputVille, {extends : 'input'})

customElements.define('table-datatable', TableDatatable, {extends : 'table'})

customElements.define('select-all', SelectAll)
customElements.define('select-element', SelectElement)
