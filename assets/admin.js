import './assets'
import './elements/admin/index'
function clickFormSave (event) {
  event.preventDefault()
  const formId = event.currentTarget.dataset.form
  const formElement = document.querySelector("form[name='" + formId + "']")
  if (formElement !== undefined) {
    formElement.submit()
  }
}
const saveForm = document.querySelectorAll('#SaveForm')
if (saveForm.length) {
  saveForm.forEach((element) => {
    element.addEventListener('click', clickFormSave)
  })
}
