// resources/js/Layouts/AppLayout.stories.js
import AppLayout from './AppLayout.vue';
import { ref } from 'vue';

export default {
  title: 'Layouts/AppLayout',
  component: AppLayout,
  parameters: {
    layout: 'fullscreen',
  },
  argTypes: {
    companyName: { control: 'text' },
    logoUrl: { control: 'text' },
    user: { control: 'object' },
    navigationItems: { control: 'object' },
    quickActions: { control: 'object' }
  }
};

const Template = (args) => ({
  components: { AppLayout },
  setup() {
    return { args };
  },
  template: `
    <AppLayout v-bind="args">
      <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Dashboard Content</h1>
        <p class="text-gray-700">This is placeholder content that would normally be your page content.</p>
      </div>
    </AppLayout>
  `
});

export const Default = Template.bind({});
Default.args = {
  companyName: 'WebSchedulr',
  logoUrl: null,
  user: {
    name: 'John Doe',
    email: 'john@example.com',
    avatar: null
  },
  navigationItems: [
    { label: 'Home', icon: 'fa-home', href: '#', active: true },
    { label: 'Calendar', icon: 'fa-calendar-alt', href: '#', active: false },
    { label: 'Sales', icon: 'fa-money-bill-wave', href: '#', active: false },
    { label: 'Clients', icon: 'fa-users', href: '#', active: false },
    { label: 'Settings', icon: 'fa-cog', href: '#', active: false }
  ],
  quickActions: [
    { label: 'New Appointment', description: 'Schedule a new appointment', icon: 'fa-calendar-plus', color: 'primary', href: '#' },
    { label: 'New Client', description: 'Add a new client', icon: 'fa-user-plus', color: 'secondary', href: '#' }
  ]
};