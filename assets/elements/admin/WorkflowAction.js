export class WorkflowAction extends HTMLElement {
  constructor () {
    super()
    if (this.innerHTML !== '') {
      return
    }
    this.classList.add('workflow-action')
    this.innerHTML = '<i></i> ' + this.dataset.name
    this.dataset.toggle = 'modal'
    this.dataset.target = '#workflowModal'
    this.addEventListener('click', this.onClick)
  }

  onClick (element) {
    element.preventDefault()
    const url = element.currentTarget.dataset.url
    const token = element.currentTarget.dataset.token
    const redirect = element.currentTarget.dataset.redirect
    const btnConfirm = document.querySelector('.confirm-workflow')
    btnConfirm.dataset.url = url
    btnConfirm.dataset.token = token
    btnConfirm.dataset.redirect = redirect
  }
}
