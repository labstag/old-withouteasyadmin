import { LinkShow } from './LinkShow'
import { LinkGuard } from './LinkGuard'
import { LinkEdit } from './LinkEdit'
import { LinkRestore } from './LinkRestore'
import { LinkDelete } from './LinkDelete'
import { LinkDestroy } from './LinkDestroy'
import { LinkBtnAdminEmpty } from './LinkBtnAdminEmpty'
import { LinkBtnAdminEmpties } from './LinkBtnAdminEmpties'
import { LinkBtnAdminEmptyAll } from './LinkBtnAdminEmptyAll'
import { LinkBtnAdminRestore } from './LinkBtnAdminRestore'
import { LinkBtnAdminDestroy } from './LinkBtnAdminDestroy'
import { LinkBtnAdmin } from './LinkBtnAdmin'
import { LinkBtnAdminDelete } from './LinkBtnAdminDelete'
import { ModalConfirmDelete } from './ModalConfirmDelete'
import { ModalConfirmDeleteAttachment } from './ModalConfirmDeleteAttachment'
import { ModalConfirmDestroy } from './ModalConfirmDestroy'
import { ModalConfirmRestore } from './ModalConfirmRestore'
import { ModalConfirmWorkflow } from './ModalConfirmWorkflow'
import { ModalConfirmEmpty } from './ModalConfirmEmpty'
import { ModalConfirmEmptyAll } from './ModalConfirmEmptyAll'
import { ModalConfirmEmpties } from './ModalConfirmEmpties'
import { WorkflowAction } from './WorkflowAction'
import { GuardGroups } from './GuardGroups'
import { GuardUsers } from './GuardUsers'
import { GuardGroup } from './GuardGroup'
import { GuardUser } from './GuardUser'
import { AttachmentImg } from './AttachmentImg'
import { AttachmentDelete } from './AttachmentDelete'
import { LinkTrash } from './LinkTrash'
import { LinkEmpty } from './LinkEmpty'

customElements.define('workflow-action', WorkflowAction)

customElements.define('link-show', LinkShow)
customElements.define('link-guard', LinkGuard)
customElements.define('link-edit', LinkEdit)
customElements.define('link-delete', LinkDelete)
customElements.define('link-restore', LinkRestore)
customElements.define('link-destroy', LinkDestroy)
customElements.define('link-trash', LinkTrash)
customElements.define('link-btnadmin', LinkBtnAdmin)

customElements.define('link-empty', LinkEmpty)
customElements.define('link-btnadminempty', LinkBtnAdminEmpty)
customElements.define('link-btnadminempties', LinkBtnAdminEmpties)
customElements.define('link-btnadminemptyall', LinkBtnAdminEmptyAll)
customElements.define('link-btnadminrestore', LinkBtnAdminRestore)
customElements.define('link-btnadmindestroy', LinkBtnAdminDestroy)
customElements.define('link-btnadmindelete', LinkBtnAdminDelete)

customElements.define('guard-groups', GuardGroups, { extends: 'table' })
customElements.define('guard-users', GuardUsers, { extends: 'table' })

customElements.define('guard-group', GuardGroup)
customElements.define('guard-user', GuardUser)

customElements.define('confirm-delete', ModalConfirmDelete)
customElements.define('confirm-deleteattachment', ModalConfirmDeleteAttachment)
customElements.define('confirm-destroy', ModalConfirmDestroy)
customElements.define('confirm-restore', ModalConfirmRestore)
customElements.define('confirm-empty', ModalConfirmEmpty)
customElements.define('confirm-emptyall', ModalConfirmEmptyAll)
customElements.define('confirm-empties', ModalConfirmEmpties)
customElements.define('confirm-workflow', ModalConfirmWorkflow)

customElements.define('attachment-img', AttachmentImg)
customElements.define('attachment-delete', AttachmentDelete)
