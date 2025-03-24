import { setup } from '@storybook/vue3';
import { createInertiaApp } from '@inertiajs/vue3';
import '../resources/css/app.css'; // Import your tailwind CSS

// Setup helpers
setup(app => {
  // Mock Inertia's route function for storybook
  app.config.globalProperties.route = (name, params = {}) => {
    return '#'; // Return a dummy URL for stories
  };
});

/** @type { import('@storybook/vue3').Preview } */
const preview = {
  parameters: {
    actions: { argTypesRegex: "^on[A-Z].*" },
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/i,
      },
    },
    layout: 'centered',
    backgrounds: {
      default: 'light',
      values: [
        {
          name: 'light',
          value: '#f3f4f6',
        },
        {
          name: 'dark',
          value: '#1f2937',
        },
      ],
    },
  },
};

export default preview;