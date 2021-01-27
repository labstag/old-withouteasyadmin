export class GuardGroups extends HTMLTableElement {
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
    const checkboxs = document.getElementsByTagName('guard-group')
    response.forEach(
      element => {
        checkboxs.forEach(
          checkbox => {
            const test1 = checkbox.dataset.groupe === element.groupe
            const test2 = checkbox.dataset.route === element.route
            if (test1 && test2) {
              checkbox.dataset.state = 1
            }
          }
        )
      }
    )
  }
}
