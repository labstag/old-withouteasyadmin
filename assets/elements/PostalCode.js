export class PostalCode extends HTMLInputElement {
  fetchResponse(response) {
    if (0 == response.length) {
      return;
    }
    let data = response[0];
    if (1 == response.length) {
      this.codepostal.value = data.postalCode;
      this.ville.value = data.placeName;
      this.gps.value = data.latitude + "," + data.longitude;
    }
  }

  ajax() {
    const params = {};
    if ('' != this.country.value) {
      params['country'] = this.country.value;
    }
    if ('' != this.ville.value) {
      params['placeName'] = this.ville.value;
    }
    if ('' != this.codepostal.value) {
      params['postalCode'] = this.codepostal.value;
    }

    fetch(this.url + '?' + new URLSearchParams(params))
        .then(response => response.json())
        .then(this.fetchResponse.bind(this))
        .catch(this.fetchCatch);
  }

  onKeydown(element) {
    clearTimeout(this.timeout);
    this.timeout = setTimeout(this.ajax.bind(this), 500);
  }

  setData() {
    this.row = this.closest(".row");
    this.inputs = this.row.getElementsByTagName('input');
    const selects = this.row.getElementsByTagName('select');
    this.country = null;
    this.codepostal = null;
    this.ville = null;
    this.gps = null;
    selects.forEach((element) => {
      let isInput = element.getAttribute('is');
      if (isInput == 'select-country') {
        this.country = element;
      }
    });
    this.inputs.forEach((element) => {
      let isInput = element.getAttribute('is');
      if ('input-codepostal' == isInput) {
        this.codepostal = element;
      } else if ('input-ville' == isInput) {
        this.ville = element;
      } else if ('input-gps' == isInput) {
        this.gps = element;
      }
    });

    this.url = this.row.dataset.url;
    this.timeout = null;
  }
}