// resources/js/Components/Dashboard/QuickActionsMenu.stories.js
import QuickActionsMenu from './QuickActionsMenu.vue';
import { ref } from 'vue';

export default {
  title: 'Components/Dashboard/QuickActionsMenu',
  component: QuickActionsMenu,
  parameters: {
    docs: {
      description: {
        component: 'Slide-over menu for quick actions like creating appointments.',
      },
    },
  },
  argTypes: {
    isOpen: { control: 'boolean' },
    actions: { control: 'object' },
  },
};

// Template for all stories
const Template = (args) => ({
  components: { QuickActionsMenu },
  setup() {
    const isOpen = ref(args.isOpen);
    
    function closeMenu() {
      isOpen.value = false;
    }
    
    function openMenu() {
      isOpen.value = true;
    }
    
    return { 
      args,
      isOpen,
      closeMenu,
      openMenu
    };
  },
  template: `
    <div class="min-h-screen bg-gray-100 p-6">
      <button 
        @click="openMenu"
        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
      >
        Open Quick Actions
      </button>
      
      <QuickActionsMenu 
        :is-open="isOpen" 
        :actions="args.actions"
        @close="closeMenu"
      />
    </div>
  `,
});

// Default story - Closed
export const Default = Template.bind({});
Default.args = {
  isOpen: false,
  actions: [
    { label: 'New Appointment', description: 'Schedule a new appointment', icon: 'fa-calendar-plus', color: 'indigo', href: '#' },
    { label: 'New Client', description: 'Add a new client', icon: 'fa-user-plus', color: 'green', href: '#' },
    { label: 'Add Service', description: 'Create a new service', icon: 'fa-tag', color: 'blue', href: '#' },
    { label: 'Send Reminder', description: 'Send reminder to clients', icon: 'fa-bell', color: 'yellow', href: '#' },
  ],
};

// Open Menu
export const OpenMenu = Template.bind({});
OpenMenu.args = {
  ...Default.args,
  isOpen: true,
};

// With Few Actions
export const FewActions = Template.bind({});
FewActions.args = {
  isOpen: true,
  actions: [
    { label: 'New Appointment', description: 'Schedule a new appointment', icon: 'fa-calendar-plus', color: 'indigo', href: '#' },
    { label: 'New Client', description: 'Add a new client', icon: 'fa-user-plus', color: 'green', href: '#' },
  ],
};