import "./elements/admin/index";

import * as assets from "./assets";

function clickFormSave(event) {
  event.preventDefault();
  const formId = event.currentTarget.dataset.form;
  document.querySelector("form[name='" + formId + "']").submit();
}
let saveForm = document.querySelectorAll("#SaveForm");
if (saveForm.length) {
  saveForm.forEach((element) => {
    element.addEventListener("click", clickFormSave);
  });
}
