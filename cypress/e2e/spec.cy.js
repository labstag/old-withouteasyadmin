describe('template spec', () => {
  it('passes', () => {
    cy.visit('https://labstag.traefik.me', {failOnStatusCode: false});
    cy.screenshot('first-page');
  })
})