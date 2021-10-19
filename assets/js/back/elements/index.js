import './attachment/index'
import './guard/index'
import './link/index'
import './modal/index'
import { MenuMove } from './MenuMove'
import { WorkflowAction } from './WorkflowAction'

window.customElements.define('workflow-action', WorkflowAction)
window.customElements.define('menu-move', MenuMove, { extends: 'ul' })
