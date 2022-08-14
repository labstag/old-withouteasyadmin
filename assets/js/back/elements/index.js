import './attachment/index'
import './guard/index'
import './link/index'
import './modal/index'
import { EntityMove } from './EntityMove'
import { WorkflowAction } from './WorkflowAction'
import Sortable from 'sortablejs'

window.customElements.define('workflow-action', WorkflowAction)
window.customElements.define('entity-move', EntityMove, { extends: 'ul' })
function loadAdmin () {
  document.querySelectorAll('.paragraph_show').forEach(
    element => {
      element.addEventListener('click', () => {
        document.getElementById('iframe_paragaph').setAttribute('src', element.getAttribute('href'))
      })
    })
  sortableElement()
}

function sortableElement () {
  const $elementSortable = document.getElementById('paragraphs-list')

  if ($elementSortable !== undefined) {
    Sortable.create(
      $elementSortable,
      {
        onChange: function (event) {
          changeParagraphInputPosition()
        }
      }
    )
  }
}

function changeParagraphInputPosition () {
  document.querySelectorAll('.paragraph_input').forEach(
    (element, position) => {
      element.ariaValueMax(position + 1)
    }
  )
}
window.addEventListener(
  'load',
  () => loadAdmin()
)
