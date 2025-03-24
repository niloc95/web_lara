// resources/js/Components/Dashboard/ActionButton.stories.js
import ActionButton from './ActionButton.vue';

export default {
  title: 'Dashboard/ActionButton',
  component: ActionButton,
  argTypes: {
    icon: {
      control: { type: 'select' },
      options: ['plus', 'calendar', 'user', 'tag', 'settings']
    },
    color: {
      control: { type: 'select' },
      options: ['indigo', 'blue', 'green', 'red', 'yellow', 'purple']
    }
  }
};

export const Default = {
  args: {
    href: '#',
    icon: 'plus',
    text: 'New Appointment',
    color: 'indigo'
  }
};

export const Calendar = {
  args: {
    href: '#',
    icon: 'calendar',
    text: 'View Calendar',
    color: 'blue'
  }
};

export const Client = {
  args: {
    href: '#',
    icon: 'user',
    text: 'New Client',
    color: 'green'
  }
};