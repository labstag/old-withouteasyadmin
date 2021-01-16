import { LinkShow } from './LinkShow';
import { LinkEdit } from './LinkEdit';
import { LinkDelete } from './LinkDelete';
import { LinkBtnAdmin } from './LinkBtnAdmin';
import { LinkBtnAdminDelete } from './LinkBtnAdminDelete';
import {ModalConfirmDelete} from './ModalConfirmDelete';

customElements.define('link-show', LinkShow);
customElements.define('link-edit', LinkEdit);
customElements.define('link-delete', LinkDelete);
customElements.define('link-btnadmin', LinkBtnAdmin);
customElements.define('link-btnadmindelete', LinkBtnAdminDelete);

customElements.define('confirm-delete', ModalConfirmDelete, { 'extends': 'button' });