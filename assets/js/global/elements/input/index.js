import { InputPhone } from './InputPhone'
import { InputCodePostal } from './InputCodePostal'
import { InputEmail } from './InputEmail'
import { InputUrl } from './InputUrl'
import { InputCity } from './InputCity'
import { InputGps } from './InputGps'

window.customElements.define('input-phone', InputPhone, { extends: 'input' })
window.customElements.define('input-email', InputEmail, { extends: 'input' })
window.customElements.define('input-url', InputUrl, { extends: 'input' })
window.customElements.define('input-gps', InputGps, { extends: 'input' })
window.customElements.define('input-codepostal', InputCodePostal, { extends: 'input' })
window.customElements.define('input-city', InputCity, { extends: 'input' })
