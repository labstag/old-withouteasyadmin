import './global'
import './elements/admin/index'
function clickFormSave (event) {
  event.preventDefault()
  const formId = event.currentTarget.dataset.form
  const formElement = document.querySelector("form[name='" + formId + "']")
  if (formElement !== null) {
    formElement.submit()
  }
}
const saveForm = document.querySelector('#SaveForm')
if (saveForm !== null) {
  saveForm.addEventListener('click', clickFormSave)
}
