const path = require('path');
const srcDir = '../assets';
const tplDir = '../apps/templates';

/** @type { import('@storybook/web-components-webpack5').StorybookConfig } */
const config = {
  stories: [
    "../stories/**/*.mdx",
    "../stories/**/*.stories.@(js|jsx|ts|tsx)"
  ],
  addons: [
    "@storybook/addon-links",
    "@storybook/addon-essentials"
  ],
  framework: {
    name: "@storybook/web-components-webpack5",
    options: {},
  },
  docs: {
    autodocs: "tag",
  },
  staticDirs: [
    {
      from: `${srcDir}/images`,
      to: '/assets/images'
    }
  ],
  async webpackFinal(config) {
    config.module.rules = [
      ...config.module.rules,
      {
        test: /\.twig$/,
        use:  {
          loader: 'twigjs-loader',
          options: {
            renderTemplate(twigData, dependencies, isHot) {
              return `
                ${dependencies}
                const Twig = require('twig');
                Twig.extendFilter('t', (value) => {
                  return value;
                });
                const twig = Twig.twig;
                const tpl = twig(${JSON.stringify(twigData)});
                module.exports = function(context) { return tpl.render(context); };
              `;
            },
          },
        },
      }
    ];
    config.resolve.alias = {
      ...config.resolve.alias,
      '@fonts': path.resolve(__dirname, `${srcDir}/fonts`),
      '@images': path.resolve(__dirname, `${srcDir}/images`),
      '@theme': path.resolve(__dirname, tplDir),
    };
    return config;
  }
};
export default config;
