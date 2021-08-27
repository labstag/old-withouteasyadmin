import Sortable from 'sortablejs'
export class MenuMove extends HTMLUListElement {
  constructor () {
    super()
    this.classList.add('menu-move')
    Sortable.create(
      this,
      {
        animation: 150,
        ghostClass: 'blue-background-class'
      }
    )
  }
}
