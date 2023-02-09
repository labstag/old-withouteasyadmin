describe('template spec', () => {
  it('passes', () => {
    cy.visit('https://labstag.traefik.me');
    cy.screenshot('first-page');
  })
})