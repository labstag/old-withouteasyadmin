export class BtnToggleFieldset extends HTMLElement{
  connectedCallback()
  {
    this.classList.add('btn-togglefieldset');
    this.innerHTML = '<i class="min"></i>';
    this.addEventListener('click', this.onclick);
  }

  onclick(element) {
    element.preventDefault();
    let iElement = element.currentTarget.querySelector("i");
    let contains = iElement.classList.contains('min');
    iElement.classList.remove('min');
    iElement.classList.remove('max');
    iElement.classList.add(contains ? 'max' : 'min');
    let fieldset = element.currentTarget.closest("fieldset");
    let fieldrow = fieldset.querySelector(".FieldRow");
    let btnCollectionAdd = fieldset.querySelector(".BtnCollectionAdd");
    if (btnCollectionAdd != null) {
      if (
        btnCollectionAdd.style.display == "" ||
        btnCollectionAdd.style.display == "block"
      ) {
        btnCollectionAdd.style.display = "none";
      } else {
        btnCollectionAdd.style.display = "block";
      }
    }
    if (fieldrow.style.display == "" || fieldrow.style.display == "block") {
      fieldrow.style.display = "none";
    } else {
      fieldrow.style.display = "block";
    }

  }
}