// resources/js/Components/Layout/Sidebar.stories.js
import Sidebar from './Sidebar.vue';

export default {
  title: 'Layout/Sidebar',
  component: Sidebar,
  parameters: {
    layout: 'centered',
  },
  argTypes: {
    navigationItems: { control: 'object' }
  }
};

const Template = (args) => ({
  components: { Sidebar },
  setup() {
    return { 
      args,
      openQuickActions: () => console.log('Quick actions opened')
    };
  },
  template: `
    <div class="h-screen flex">
      <Sidebar v-bind="args" @open-quick-actions="openQuickActions" />
    </div>
  `
});

export const Default = Template.bind({});
Default.args = {
  navigationItems: [
    { label: 'Home', icon: 'fa-home', href: '#', active: true },
    { label: 'Calendar', icon: 'fa-calendar-alt', href: '#', active: false },
    { label: 'Sales', icon: 'fa-money-bill-wave', href: '#', active: false },
    { label: 'Clients', icon: 'fa-users', href: '#', active: false },
    { label: 'Reports', icon: 'fa-chart-bar', href: '#', active: false },
    { label: 'Settings', icon: 'fa-cog', href: '#', active: false }
  ]
};

// With Different Active Item
export const WithDifferentActiveItem = Template.bind({});
WithDifferentActiveItem.args = {
  navigationItems: [
    { label: 'Dashboard', icon: 'fa-home', href: '#', active: false },
    { label: 'Calendar', icon: 'fa-calendar-alt', href: '#', active: true },
    { label: 'Appointments', icon: 'fa-calendar-check', href: '#', active: false },
    { label: 'Clients', icon: 'fa-users', href: '#', active: false },
    { label: 'Reports', icon: 'fa-chart-bar', href: '#', active: false },
    { label: 'Settings', icon: 'fa-cog', href: '#', active: false },
  ],
};

// Few Items
export const FewItems = Template.bind({});
FewItems.args = {
  navigationItems: [
    { label: 'Dashboard', icon: 'fa-home', href: '#', active: true },
    { label: 'Calendar', icon: 'fa-calendar-alt', href: '#', active: false },
    { label: 'Settings', icon: 'fa-cog', href: '#', active: false },
  ],
};

// Many Items
export const ManyItems = Template.bind({});
ManyItems.args = {
  navigationItems: [
    { label: 'Dashboard', icon: 'fa-home', href: '#', active: true },
    { label: 'Calendar', icon: 'fa-calendar-alt', href: '#', active: false },
    { label: 'Appointments', icon: 'fa-calendar-check', href: '#', active: false },
    { label: 'Clients', icon: 'fa-users', href: '#', active: false },
    { label: 'Services', icon: 'fa-tag', href: '#', active: false },
    { label: 'Staff', icon: 'fa-user-tie', href: '#', active: false },
    { label: 'Reports', icon: 'fa-chart-bar', href: '#', active: false },
    { label: 'Marketing', icon: 'fa-bullhorn', href: '#', active: false },
    { label: 'Settings', icon: 'fa-cog', href: '#', active: false },
    { label: 'Help', icon: 'fa-question-circle', href: '#', active: false },
  ],
};