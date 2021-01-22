import { LinkBtnAdmin } from "./LinkBtnAdmin";
import { LinkBtnAdminDelete } from "./LinkBtnAdminDelete";
import { LinkBtnAdminDestroy } from "./LinkBtnAdminDestroy";
import { LinkBtnAdminEmpty } from "./LinkBtnAdminEmpty";
import { LinkBtnAdminRestore } from "./LinkBtnAdminRestore";
import { LinkDelete } from "./LinkDelete";
import { LinkDestroy } from "./LinkDestroy";
import { LinkEdit } from "./LinkEdit";
import { LinkRestore } from "./LinkRestore";
import { LinkShow } from "./LinkShow";
import { ModalConfirmDelete } from "./ModalConfirmDelete";
import { ModalConfirmDestroy } from "./ModalConfirmDestroy";
import { ModalConfirmEmpty } from "./ModalConfirmEmpty";
import { ModalConfirmRestore } from "./ModalConfirmRestore";
import { ModalConfirmWorkflow } from "./ModalConfirmWorkflow";
import { WorkflowAction } from "./WorkflowAction";

customElements.define("workflow-action", WorkflowAction);

customElements.define("link-show", LinkShow);
customElements.define("link-edit", LinkEdit);
customElements.define("link-delete", LinkDelete);
customElements.define("link-restore", LinkRestore);
customElements.define("link-destroy", LinkDestroy);
customElements.define("link-btnadmin", LinkBtnAdmin);
customElements.define("link-btnadminempty", LinkBtnAdminEmpty);
customElements.define("link-btnadminrestore", LinkBtnAdminRestore);
customElements.define("link-btnadmindestroy", LinkBtnAdminDestroy);
customElements.define("link-btnadmindelete", LinkBtnAdminDelete);

customElements.define("confirm-delete", ModalConfirmDelete, {
  extends: "button",
});
customElements.define("confirm-destroy", ModalConfirmDestroy, {
  extends: "button",
});
customElements.define("confirm-restore", ModalConfirmRestore, {
  extends: "button",
});
customElements.define("confirm-empty", ModalConfirmEmpty, {
  extends: "button",
});
customElements.define("confirm-workflow", ModalConfirmWorkflow, {
  extends: "button",
});
