import CalendarNavigation from './CalendarNavigation.vue';

export default {
  title: 'Calendar/CalendarNavigation',
  component: CalendarNavigation,
  tags: ['autodocs'],
  argTypes: {
    title: { control: 'text' },
    subtitle: { control: 'text' },
    currentView: { 
      control: 'select', 
      options: ['month', 'week', 'day']
    },
    previous: { action: 'previous' },
    next: { action: 'next' },
    today: { action: 'today' },
    'change-view': { action: 'change-view' }
  }
};

export const Default = {
  args: {
    title: 'March 2025',
    subtitle: 'Today is March 23, 2025',
    currentView: 'month'
  }
};

export const WeekView = {
  args: {
    title: 'Week of March 23 - 29, 2025',
    subtitle: 'Today is March 23, 2025',
    currentView: 'week'
  }
};

export const DayView = {
  args: {
    title: 'March 23, 2025',
    subtitle: 'Sunday',
    currentView: 'day'
  }
};