import './attachment/index'
import './guard/index'
import './link/index'
import './modal/index'
import { EntityMove } from './EntityMove'
import { WorkflowAction } from './WorkflowAction'

window.customElements.define('workflow-action', WorkflowAction)
window.customElements.define('entity-move', EntityMove, { extends: 'ul' })
function loadAdmin() {
  document.querySelectorAll('.paragraph_show').forEach(
    element => {
      element.addEventListener('click', () => {
        document.getElementById('iframe_paragaph').setAttribute('src', element.getAttribute('href'))
      })
  })
}
window.addEventListener(
  'load',
  () => loadAdmin()
)