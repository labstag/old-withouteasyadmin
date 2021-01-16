export class InputUrl extends HTMLInputElement {
  connectedCallback() {
    this.timeout = null;
    this.addEventListener('keydown', this.onKeydown);
    this.onKeydown();
  }

  traitement() {
    this.classList.remove('is-valid');
    this.classList.remove('is-invalid');
    console.log(this.checkValidity());
    this.classList.add(this.checkValidity() ? 'is-valid' : 'is-invalid');
  }

  onKeydown() {
    clearTimeout(this.timeout);
    this.timeout = setTimeout(this.traitement.bind(this), 500);
  }
}