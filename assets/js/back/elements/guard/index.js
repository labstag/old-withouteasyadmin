import { GuardAllRoute } from './GuardAllRoute'
import { GuardAllWorkflow } from './GuardAllWorkflow'
import { GuardChangeRoute } from './GuardChangeRoute'
import { GuardChangeWorkflow } from './GuardChangeWorkflow'
import { GuardRefGroupRoute } from './GuardRefGroupRoute'
import { GuardRefGroupWorkflow } from './GuardRefGroupWorkflow'
import { GuardRoute } from './GuardRoute'
import { GuardSetRoute } from './GuardSetRoute'
import { GuardSetWorkflow } from './GuardSetWorkflow'
import { GuardWorkflow } from './GuardWorkflow'

window.customElements.define('guard-allroute', GuardAllRoute)
window.customElements.define('guard-allworkflow', GuardAllWorkflow)
window.customElements.define('guard-changeroute', GuardChangeRoute)
window.customElements.define('guard-changeworkflow', GuardChangeWorkflow)
window.customElements.define('guard-refgrouproute', GuardRefGroupRoute)
window.customElements.define('guard-refgroupworkflow', GuardRefGroupWorkflow)
window.customElements.define('guard-route', GuardRoute, { extends: 'table' })
window.customElements.define('guard-setroute', GuardSetRoute)
window.customElements.define('guard-setworkflow', GuardSetWorkflow)
window.customElements.define('guard-workflow', GuardWorkflow, { extends: 'table' })
