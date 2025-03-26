import { ref } from 'vue';

// Mock Inertia's usePage
export const usePage = () => {
  return {
    props: ref({
      value: {
        businessSettings: {
          name: 'Story Business',
        },
        appName: 'WebSchedulr',
        auth: {
          user: {
            name: 'John Doe',
            email: 'john@example.com',
          },
        },
      },
    }),
  };
};

// Mock eventBus
export const eventBus = {
  on: () => {},
  off: () => {},
  emit: () => {},
};