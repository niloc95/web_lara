import BusinessHours from './resources/js/Components/Settings/BusinessHours.vue';
import { ref } from 'vue';

export default {
  title: 'Settings/BusinessHours',
  component: BusinessHours,
  parameters: {
    layout: 'centered',
  },
  argTypes: {
    modelValue: { control: 'object' },
  },
};

// Create a template for testing
const Template = (args) => ({
  components: { BusinessHours },
  setup() {
    const hours = ref(args.modelValue);
    return { hours, args };
  },
  template: `
    <div class="p-6 max-w-3xl w-full bg-neutral-50 dark:bg-neutral-900 rounded-lg">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Business Hours</h2>
      <BusinessHours v-model="hours" />
      
      <div class="mt-8">
        <h3 class="text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Current Value:</h3>
        <pre class="bg-white dark:bg-neutral-800 p-4 rounded text-xs overflow-auto max-h-80">{{ JSON.stringify(hours, null, 2) }}</pre>
      </div>
    </div>
  `,
});

// Default story - weekdays 9-5
export const Default = Template.bind({});
Default.args = {
  modelValue: [
    { dayName: 'Sunday', isOpen: false, ranges: [] },
    { dayName: 'Monday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 17 * 60 }] },
    { dayName: 'Tuesday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 17 * 60 }] },
    { dayName: 'Wednesday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 17 * 60 }] },
    { dayName: 'Thursday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 17 * 60 }] },
    { dayName: 'Friday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 17 * 60 }] },
    { dayName: 'Saturday', isOpen: false, ranges: [] },
  ],
};

// Extended hours with multiple shifts
export const ExtendedHours = Template.bind({});
ExtendedHours.args = {
  modelValue: [
    { dayName: 'Sunday', isOpen: true, ranges: [{ startTime: 10 * 60, endTime: 14 * 60 }] },
    { dayName: 'Monday', isOpen: true, ranges: [
      { startTime: 9 * 60, endTime: 13 * 60 },
      { startTime: 14 * 60, endTime: 18 * 60 }
    ]},
    { dayName: 'Tuesday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 19 * 60 }] },
    { dayName: 'Wednesday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 19 * 60 }] },
    { dayName: 'Thursday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 19 * 60 }] },
    { dayName: 'Friday', isOpen: true, ranges: [{ startTime: 9 * 60, endTime: 17 * 60 }] },
    { dayName: 'Saturday', isOpen: true, ranges: [{ startTime: 10 * 60, endTime: 15 * 60 }] },
  ],
};

// Empty state
export const Empty = Template.bind({});
Empty.args = {
  modelValue: [],
};