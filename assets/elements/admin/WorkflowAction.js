export class WorkflowAction extends HTMLElement {
  constructor() {
    super();
    if ('' != this.innerHTML) {
      return;
    }
    this.classList.add('workflow-action');
    this.innerHTML = "<i></i> " + this.dataset.name;
    this.dataset.toggle = "modal";
    this.dataset.target = "#workflowModal";
    this.addEventListener('click', this.onClick);
  }

  onClick(element) {
    element.preventDefault();
    let url = element.currentTarget.dataset.url;
    let token = element.currentTarget.dataset.token;
    let redirect = element.currentTarget.dataset.redirect;
    let btnConfirm = document.querySelector(".confirm-workflow");
    btnConfirm.dataset.url = url;
    btnConfirm.dataset.token = token;
    btnConfirm.dataset.redirect = redirect;
  }
}