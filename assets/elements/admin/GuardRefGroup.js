export class GuardRefGroup extends HTMLElement {
  constructor () {
    super()
    this.changeState()
    const observer = new MutationObserver(this.mutationObserver.bind(this))
    observer.observe(this, {
      attributes: true
    })
  }

  mutationObserver (mutations) {
    mutations.forEach(this.forEachMutationObserver.bind(this))
  }

  forEachMutationObserver (mutation) {
    if (mutation.type === 'attributes' && mutation.attributeName === 'data-state') {
      this.changeState()
    }
  }

  changeState () {
    this.setAttribute('class', (this.dataset.state === '1') ? 'checkOK' : 'checkKO')
  }
}
