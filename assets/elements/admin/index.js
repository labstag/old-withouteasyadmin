import { LinkBtnAdmin } from "./LinkBtnAdmin";
import { LinkBtnAdminDelete } from "./LinkBtnAdminDelete";
import { LinkDelete } from "./LinkDelete";
import { LinkEdit } from "./LinkEdit";
import { LinkShow } from "./LinkShow";
import { ModalConfirmDelete } from "./ModalConfirmDelete";

customElements.define("link-show", LinkShow);
customElements.define("link-edit", LinkEdit);
customElements.define("link-delete", LinkDelete);
customElements.define("link-btnadmin", LinkBtnAdmin);
customElements.define("link-btnadmindelete", LinkBtnAdminDelete);

customElements.define("confirm-delete", ModalConfirmDelete, {
  extends: "button",
});
