import * as assets from './assets';
function clickDeleteEntity(event)
{
  event.preventDefault();
  let url = event.currentTarget.dataset.url;
  let token = event.currentTarget.dataset.token;
  let redirect = event.currentTarget.dataset.redirect;
  let btnConfirm = document.querySelector('.BtnConfirmDelete');
  btnConfirm.dataset.url = url;
  btnConfirm.dataset.token = token;
  btnConfirm.dataset.redirect = redirect;
}

function clickFormSave(event)
{
  event.preventDefault();
  const formId = event.currentTarget.dataset.form;
  document.querySelector("form[name='"+formId+"']").submit();
}

function clickbtnConfirmDelete(event) {
  const element = event.currentTarget;
  const url = element.dataset.url;
  const token = element.dataset.token;
  const redirect = element.dataset.redirect;
  const data = {
    '_token': token
  };
  const searchParams = Object.keys(data).map((key) => {
    return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
  }).join('&');
  let options = {
    method: 'POST',
    headers: {
      "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
    },
    body: searchParams
  };
  fetch(
    url,
    options
  ).then(
    response => {
      window.location.href = redirect;
    }
  ).catch(
    err => {
      console.log(err)
    }
  );
}
let saveForm = document.querySelectorAll('#SaveForm');
if (saveForm.length) {
  saveForm.forEach(element => {
    element.addEventListener('click', clickFormSave);
  })
}
let btnConfirmDeletes = document.querySelectorAll('.BtnConfirmDelete');
if (btnConfirmDeletes.length) {
  btnConfirmDeletes.forEach(element => {
    element.addEventListener('click', clickbtnConfirmDelete);
  });
}
let deleteForms = document.querySelectorAll('#DeleteForm');
if (deleteForms.length) {
  deleteForms.forEach(element => {
    element.addEventListener('click', clickDeleteEntity);
  });
}

window.operateEvents = {
  'click .btnAdminTableDelete': function (event) {
    clickDeleteEntity(event);
  }
}