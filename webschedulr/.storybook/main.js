/** @type { import('@storybook/vue3-vite').StorybookConfig } */
const config = {
  stories: [
    "../resources/js/**/*.stories.mdx",
    "../resources/js/**/*.stories.@(js|jsx|ts|tsx)"
  ],
  addons: [
    "@storybook/addon-links",
    "@storybook/addon-essentials",
    "@storybook/addon-interactions",
    "@storybook/addon-a11y",
  ],
  framework: {
    name: "@storybook/vue3-vite",
    options: {}
  },
  docs: {
    autodocs: true
  },
  staticDirs: ['../public'],
};

export default config;