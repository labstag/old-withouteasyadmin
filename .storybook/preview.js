/** @type { import('@storybook/web-components').Preview } */
const preview = {
  parameters: {
    actions: {
      argTypesRegex: "^on[A-Z].*"
    },
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/,
      },
    },
    options: {
      storySort: {
        method: 'alphabetical',
        order: [ 'Introduction', 'Atoms', 'Molecules', 'Organisms', 'Templates', 'Pages' ],
        locales: '',
      },
    },
  },
};

export default preview;
