// resources/js/Components/Charts/SimpleDoughnutChart.stories.js
import SimpleDoughnutChart from './SimpleDoughnutChart.vue';

export default {
  title: 'Components/Charts/SimpleDoughnutChart',
  component: SimpleDoughnutChart,
  parameters: {
    docs: {
      description: {
        component: 'Simple doughnut chart component for displaying distribution data.',
      },
    },
  },
  argTypes: {
    chartData: { control: 'object' },
  },
};

const Template = (args) => ({
  components: { SimpleDoughnutChart },
  setup() {
    return { args };
  },
  template: '<div style="width: 300px; height: 300px;"><SimpleDoughnutChart v-bind="args" /></div>',
});

export const Default = Template.bind({});
Default.args = {
  chartData: {
    labels: ['Confirmed', 'Pending', 'Cancelled', 'Completed'],
    datasets: [{ 
      data: [10, 5, 3, 15],
      backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#3B82F6']
    }]
  },
};