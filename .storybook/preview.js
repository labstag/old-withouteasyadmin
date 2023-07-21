/** @type { import('@storybook/web-components').Preview } */
import './../assets/back.js';
import './../assets/front.js';
// import * as back from './../assets/back.js';
// import * as front from './../assets/front.js';
// import { withThemeFromJSXProvider } from "@storybook/addon-styling";


// export const decorators = [
//   withThemeFromJSXProvider({
//     themes: {
//       front: front,
//       back: back
//     },
//     defaultTheme: "front"
//   }),
// ];
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
