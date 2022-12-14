import { InputCity } from './InputCity'
import { InputCodePostal } from './InputCodePostal'
import { InputEmail } from './InputEmail'
import { InputGps } from './InputGps'
import { InputPhone } from './InputPhone'
import { InputUrl } from './InputUrl'

window.customElements.define('input-city', InputCity, { extends: 'input' })
window.customElements.define('input-codepostal', InputCodePostal, { extends: 'input' })
window.customElements.define('input-email', InputEmail, { extends: 'input' })
window.customElements.define('input-gps', InputGps, { extends: 'input' })
window.customElements.define('input-phone', InputPhone, { extends: 'input' })
window.customElements.define('input-url', InputUrl, { extends: 'input' })
