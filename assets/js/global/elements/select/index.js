import { SelectAll } from './SelectAll'
import { SelectCountry } from './SelectCountry'
import { SelectElement } from './SelectElement'
import { SelectParagraph } from './SelectParagraph'
import { SelectSelector } from './SelectSelector'

window.customElements.define('select-all', SelectAll)
window.customElements.define('select-country', SelectCountry, { extends: 'select' })
window.customElements.define('select-element', SelectElement)
window.customElements.define('select-paragraph', SelectParagraph, { extends: 'select' })
window.customElements.define('select-selector', SelectSelector, { extends: 'select' })
