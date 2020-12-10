require("bootstrap");
require("bootstrap-table");
require("select2");
import bsCustomFileInput from "bs-custom-file-input";
bsCustomFileInput.init();
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
function clickToggleFieldset(event) {
  event.preventDefault();
  let ihtmls = event.currentTarget.querySelectorAll("i");
  let className = "d-none";
  ihtmls.forEach((ihtml) => {
    if (ihtml.classList.contains(className)) {
      ihtml.classList.remove(className);
    } else {
      ihtml.classList.add(className);
    }
  });
  let fieldset = event.currentTarget.closest("fieldset");
  let fieldrow = fieldset.querySelector(".FieldRow");
  let btnCollectionAdd = fieldset.querySelector(".BtnCollectionAdd");
  if (btnCollectionAdd != null) {
    if (btnCollectionAdd.style.display == "" ||
        btnCollectionAdd.style.display == "block") {
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
function clickEnableDeleteCollection(event) {
  event.preventDefault();
  let CollectionRow = event.currentTarget.closest(".CollectionRow");
  CollectionRow.parentNode.removeChild(CollectionRow);
}
function EnableDeleteCollection() {
  let btnCollectionDeletes = document.querySelectorAll(".BtnCollectionDelete");
  btnCollectionDeletes.forEach((btnCollectionDelete) => {
    btnCollectionDelete.addEventListener("click", clickEnableDeleteCollection);
  });
}
function clickbtnCollectionAdd(event) {
  event.preventDefault();
  let fieldset = event.currentTarget.closest("fieldset");
  let counter = fieldset.querySelectorAll(".CollectionRow").length;
  let html = fieldset.dataset.prototype.replace(/__name__/g, counter);
  let FieldRow = fieldset.querySelector(".FieldRow");
  FieldRow.innerHTML = FieldRow.innerHTML + html;
  EnableDeleteCollection();
}
let wysiwygs = document.querySelectorAll(".wysiwyg");
if (wysiwygs.length) {
  wysiwygs.forEach((element) => {
    ClassicEditor.create(element)
        .then((editor) => { window.editor = editor; })
        .catch((error) => {
          console.error("There was a problem initializing the editor.", error);
        });
  });
}
EnableDeleteCollection();
let btnCollectionAdds = document.querySelectorAll(".BtnCollectionAdd");
btnCollectionAdds.forEach((btnCollectionAdd) => {
  btnCollectionAdd.addEventListener("click", clickbtnCollectionAdd);
});
let toggleFieldsets = document.querySelectorAll(".ToggleFieldset");
toggleFieldsets.forEach((toggleFieldset) => {
  toggleFieldset.addEventListener("click", clickToggleFieldset);
});

let selects = document.querySelectorAll("select");
selects.forEach((select) => {
  let id = select.getAttribute("id");
  $("#" + id).select2({theme : "bootstrap4"});
});
