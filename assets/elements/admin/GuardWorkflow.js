export class GuardWorkflow extends HTMLTableElement {
  constructor () {
    super()
    const observer = new MutationObserver(this.mutationObserver.bind(this))
    observer.observe(this, {
      attributes: true
    })
    this.fetchLaunch()
  }

  mutationObserver (mutations) {
    mutations.forEach(this.forEachMutationObserver.bind(this))
  }

  forEachMutationObserver (mutation) {
    if (mutation.type === 'attributes' && mutation.attributeName === 'data-refresh') {
      this.fetchLaunch()
    }
  }

  fetchLaunch () {
    fetch(this.dataset.url)
      .then(response => response.json())
      .then(this.fetchResponse.bind(this))
      .catch(this.fetchCatch)
  }

  fetchCatch (err) {
    console.log(err)
  }

  fetchResponse (response) {
    console.log(response)
    // if (response.group !== undefined) {
    //   const refgroup = document.getElementsByTagName('guard-refgroup')
    //   if (refgroup.length !== 0 && response.group.length !== 0) {
    //     response.group.forEach(
    //       element => {
    //         refgroup.forEach(
    //           refgroup => {
    //             refgroup.dataset.state = (refgroup.dataset.group === element.groupe && refgroup.dataset.transition === element.transition && refgroup.dataset.entity === element.entity) ? 1 : 0
    //           }
    //         )
    //       }
    //     )
    //   }
    //   const guardWorkflowSetGroups = document.getElementsByTagName('guard-workflowetgroup')
    //   if (guardWorkflowSetGroups.length !== 0 && response.group.length !== 0) {
    //     response.group.forEach(
    //       element => {
    //         guardWorkflowSetGroups.forEach(
    //           guardWorkflowSetGroup => {
    //             guardWorkflowSetGroup.dataset.state = (guardWorkflowSetGroup.dataset.groupe === element.groupe && guardWorkflowSetGroup.dataset.transition === element.transition && guardWorkflowSetGroup.dataset.entity === element.entity) ? 1 : 0
    //           }
    //         )
    //       }
    //     )
    //   }
    // }
    // if (response.user !== undefined) {
    //   const guardWorkflowSetUsers = document.getElementsByTagName('guard-workflowetuser')
    //   if (guardWorkflowSetUsers.length !== 0 && response.user.length !== 0) {
    //     response.user.forEach(
    //       element => {
    //         guardWorkflowSetUsers.forEach(
    //           guardWorkflowSetUser => {
    //             guardWorkflowSetUser.dataset.state = (guardWorkflowSetUser.dataset.transition === element.transition && guardWorkflowSetUser.dataset.entity === element.entity) ? 1 : 0
    //           }
    //         )
    //       }
    //     )
    //   }
    // }
  }
}
