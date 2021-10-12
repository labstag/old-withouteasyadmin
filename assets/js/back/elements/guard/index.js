import { GuardRoute } from './GuardRoute'
import { GuardWorkflow } from './GuardWorkflow'
import { GuardRefGroupRoute } from './GuardRefGroupRoute'
import { GuardRefGroupWorkflow } from './GuardRefGroupWorkflow'
import { GuardSetWorkflow } from './GuardSetWorkflow'
import { GuardSetRoute } from './GuardSetRoute'
import { GuardAllWorkflow } from './GuardAllWorkflow'
import { GuardAllRoute } from './GuardAllRoute'
import { GuardChangeWorkflow } from './GuardChangeWorkflow'
import { GuardChangeRoute } from './GuardChangeRoute'

window.customElements.define('guard-route', GuardRoute, { extends: 'table' })
window.customElements.define('guard-workflow', GuardWorkflow, { extends: 'table' })
window.customElements.define('guard-setworkflow', GuardSetWorkflow)
window.customElements.define('guard-setroute', GuardSetRoute)
window.customElements.define('guard-allworkflow', GuardAllWorkflow)
window.customElements.define('guard-allroute', GuardAllRoute)
window.customElements.define('guard-changeworkflow', GuardChangeWorkflow)
window.customElements.define('guard-changeroute', GuardChangeRoute)
window.customElements.define('guard-refgrouproute', GuardRefGroupRoute)
window.customElements.define('guard-refgroupworkflow', GuardRefGroupWorkflow)
