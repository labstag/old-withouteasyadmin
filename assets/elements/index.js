import { BtnAddCollection } from "./BtnAddCollection";
import { BtnDelete } from "./BtnDelete";
import { BtnToggleFieldset } from "./BtnToggleFieldset";
import { InputCodePostal } from "./InputCodePostal";
import { InputEmail } from "./InputEmail";
import { InputGps } from "./InputGps";
import { InputPhone } from "./InputPhone";
import { InputUrl } from "./InputUrl";
import { InputVille } from "./InputVille";
import { SelectCountry } from "./SelectCountry";
import { SelectRefUser } from "./SelectRefUser";
import { SelectSelector } from "./SelectSelector";
import { TableDatatable } from "./TableDatatable";
import { Wysiwyg } from "./Wysiwyg";

customElements.define("btn-addcollection", BtnAddCollection);
customElements.define("btn-delete", BtnDelete);
customElements.define("btn-togglefieldset", BtnToggleFieldset);
customElements.define("textarea-wysiwyg", Wysiwyg, { extends: "textarea" });

customElements.define("select-country", SelectCountry, { extends: "select" });
customElements.define("select-selector", SelectSelector, { extends: "select" });
customElements.define("select-refuser", SelectRefUser, { extends: "select" });

customElements.define("input-phone", InputPhone, { extends: "input" });
customElements.define("input-email", InputEmail, { extends: "input" });
customElements.define("input-url", InputUrl, { extends: "input" });
customElements.define("input-gps", InputGps, { extends: "input" });
customElements.define("input-codepostal", InputCodePostal, {
  extends: "input",
});
customElements.define("input-ville", InputVille, { extends: "input" });

customElements.define("table-datatable", TableDatatable, { extends: "table" });
