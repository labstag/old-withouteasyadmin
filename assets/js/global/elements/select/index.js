import { SelectSelector } from './SelectSelector'
import { SelectCountry } from './SelectCountry'
import { SelectAll } from './SelectAll'
import { SelectElement } from './SelectElement'

window.customElements.define('select-country', SelectCountry, { extends: 'select' })
window.customElements.define('select-selector', SelectSelector, { extends: 'select' })
window.customElements.define('select-all', SelectAll)
window.customElements.define('select-element', SelectElement)
