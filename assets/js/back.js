import './global'
import './back/elements/index'
function clickFormSave(event) {
  event.preventDefault()
  const formId = event.currentTarget.getAttribute('form')
  const formElement = document.querySelector("form[name='" + formId + "']")
  if (formElement !== null) {
    formElement.submit()
  }
}

function clickReset(event) {
  event.preventDefault()
  const element = event.currentTarget
  window.location.href = element.closest('form').getAttribute('action')
}

const saveForm = document.querySelector('#SaveForm')
if (saveForm !== null) {
  saveForm.addEventListener('click', clickFormSave)
}
const resetForm = document.querySelector('#reset')
if (resetForm !== null) {
  resetForm.addEventListener('click', clickReset)
}
