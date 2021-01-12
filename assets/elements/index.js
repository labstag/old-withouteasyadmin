import { BtnDelete } from './BtnDelete';
import { BtnToggleFieldset } from './BtnToggleFieldset';
import { BtnAddCollection } from './BtnAddCollection';
import { Wysiwyg } from './Wysiwyg';
import { SelectSelector } from './SelectSelector';
import { SelectUser } from './SelectUser';
import { InputPhone } from './InputPhone';
import { InputCodePostal } from './InputCodePostal';
import { InputVille } from './InputVille';

customElements.define('btn-addcollection', BtnAddCollection);
customElements.define('btn-delete', BtnDelete);
customElements.define('btn-togglefieldset', BtnToggleFieldset);
customElements.define('textarea-wysiwyg', Wysiwyg, {'extends': 'textarea'});
customElements.define('select-selector', SelectSelector, {'extends': 'select'});
customElements.define('select-user', SelectUser, {'extends': 'select'});
customElements.define('input-phone', InputPhone, {'extends': 'input'});
customElements.define('input-codepostal', InputCodePostal, {'extends': 'input'});
customElements.define('input-ville', InputVille, {'extends': 'input'});