export class ElementHTML extends HTMLElement {
  setMutations () {
    this.changeState()
    const observer = new MutationObserver(this.mutationObserver.bind(this))
    observer.observe(this, {
      attributes: true
    })
  }

  mutationObserver (mutations) {
    mutations.forEach(this.forEachMutationObserver.bind(this))
  }

  fetchRedirect (url, options, redirect) {
    fetch(url, options)
      .then((response) => {
        window.location.href = redirect
      })
      .catch((err) => {
        console.log(err)
      })
  }
}
