import { setup } from '@storybook/vue3';
import { createInertiaApp } from '@inertiajs/vue3';
import '../resources/css/app.css'; // Import your tailwind CSS

// Setup helpers
setup(app => {
  // Mock Inertia's route function for storybook
  app.config.globalProperties.route = (name, params = {}) => {
    return '#'; // Return a dummy URL for stories
  };

  // Add dark mode support
  app.config.globalProperties.toggleDarkMode = () => {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('color-theme', 'light');
    } else {
      document.documentElement.classList.add('dark');
      localStorage.setItem('color-theme', 'dark');
    }
  }
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
    // Dark mode toolbar
    darkMode: {
      stylePreview: true, // Adds a dark mode toggle in the toolbar
    }
  },
};

export default preview;