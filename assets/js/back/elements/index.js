import './attachment/index'
import './guard/index'
import './link/index'
import './modal/index'
import { EntityMove } from './EntityMove'
import { WorkflowAction } from './WorkflowAction'
// import Sortable from 'sortablejs'

window.customElements.define('workflow-action', WorkflowAction)
window.customElements.define('entity-move', EntityMove, { extends: 'ul' })
