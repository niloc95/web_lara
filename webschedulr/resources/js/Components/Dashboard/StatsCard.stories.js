// resources/js/Components/Dashboard/StatsCard.stories.js
import StatsCard from './StatsCard.vue';

export default {
  title: 'Dashboard/StatsCard',
  component: StatsCard,
  parameters: {
    layout: 'centered',
  },
  argTypes: {
    title: { control: 'text' },
    value: { control: 'text' },
    color: { 
      control: { type: 'select' },
      options: ['primary', 'secondary', 'green', 'blue', 'yellow', 'red']
    },
    icon: { control: 'text' }
  }
};

const Template = (args) => ({
  components: { StatsCard },
  setup() {
    return { args };
  },
  template: '<div class="w-64"><StatsCard v-bind="args" /></div>'
});

export const Default = Template.bind({});
Default.args = {
  title: 'Upcoming Appointments',
  value: '12',
  color: 'primary',
  icon: 'fa-calendar-check'
};

export const Revenue = Template.bind({});
Revenue.args = {
  title: 'Weekly Revenue',
  value: 'ZAR 12,500',
  color: 'green',
  icon: 'fa-money-bill-wave'
};

export const Clients = Template.bind({});
Clients.args = {
  title: 'Active Clients',
  value: '42',
  color: 'blue',
  icon: 'fa-users'
};