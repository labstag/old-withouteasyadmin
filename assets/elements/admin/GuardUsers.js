export class GuardUsers extends HTMLTableElement {
  constructor () {
    super()
    fetch(this.dataset.url)
      .then(response => response.json())
      .then(this.fetchResponse.bind(this))
      .catch(this.fetchCatch)
  }

  fetchCatch (err) {
    console.log(err)
  }

  fetchResponse (response) {
    const checkboxs = document.getElementsByTagName('guard-user')
    response.user.forEach(
      element => {
        checkboxs.forEach(
          checkbox => {
            if (checkbox.dataset.route === element.route) {
              checkbox.dataset.state = 1
            }
          }
        )
      }
    )
    const refgroups = document.getElementsByTagName('guard-refgroup')
    response.groups.forEach(
      element => {
        refgroups.forEach(
          refgroup => {
            let check = 'KO';
            if (refgroup.dataset.route === element.route) {
              check = 'OK';
            }
            refgroup.innerHTML = '<span class="check'+check+'"></span>';
          }
        )
      }
    )
  }
}
