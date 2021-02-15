import { GuardSet } from './GuardSet'
export class GuardAllRoute extends GuardSet {
  forEachMutationObserver (mutation) {
    if (mutation.type === 'attributes') {
      if (mutation.attributeName === 'data-state') {
        this.changeState()
      } else if (mutation.attributeName === 'data-check') {
        this.changeCheck()
      }
    }
  }

  changeCheck () {
    if (this.dataset.check === '1') {
      const setRouteElement = this.closest('tr').querySelectorAll('guard-setroute')
      let state = 0
      setRouteElement.forEach(
        element => {
          state += parseInt(element.dataset.state)
        }
      )
      if (state === setRouteElement.length) {
        this.dataset.state = 1
      } else {
        this.dataset.state = 0
      }
      this.dataset.check = 0
    }
  }
}
