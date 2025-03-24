import AppointmentCard from './AppointmentCard.vue';

export default {
  title: 'Calendar/AppointmentCard',
  component: AppointmentCard,
  tags: ['autodocs'],
  argTypes: {
    appointment: { control: 'object' },
    onClick: { action: 'clicked' }
  }
};

// Base template
export const Default = {
  args: {
    appointment: {
      id: 1,
      client: { name: 'John Doe' },
      service: { name: 'Haircut', duration: 30 },
      start_time: '2025-03-23T10:00:00',
      status: 'confirmed'
    }
  },
  render: (args) => ({
    components: { AppointmentCard },
    setup() {
      return { args };
    },
    template: '<div class="w-64"><AppointmentCard v-bind="args" @click="onClick" /></div>',
    methods: {
      onClick() {
        console.log('Appointment clicked');
      }
    }
  })
};

// Status variants
export const Pending = {
  args: {
    appointment: {
      ...Default.args.appointment,
      status: 'pending'
    }
  },
  render: Default.render
};

export const Completed = {
  args: {
    appointment: {
      ...Default.args.appointment,
      status: 'completed'
    }
  },
  render: Default.render
};

export const Cancelled = {
  args: {
    appointment: {
      ...Default.args.appointment,
      status: 'cancelled'
    }
  },
  render: Default.render
};