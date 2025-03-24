// resources/js/Components/Charts/SimpleBarChart.stories.js
import SimpleBarChart from './SimpleBarChart.vue';

export default {
  title: 'Components/Charts/SimpleBarChart',
  component: SimpleBarChart,
  parameters: {
    docs: {
      description: {
        component: 'Simple bar chart component for displaying trend data.',
      },
    },
  },
  argTypes: {
    chartData: { control: 'object' },
  },
};

const Template = (args) => ({
  components: { SimpleBarChart },
  setup() {
    return { args };
  },
  template: '<div style="width: 600px; height: 300px;"><SimpleBarChart v-bind="args" /></div>',
});

export const Default = Template.bind({});
Default.args = {
  chartData: {
    labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    datasets: [{ 
      data: [5, 12, 8, 15, 10, 20, 7],
      backgroundColor: '#4e73df'
    }]
  },
};

export const MultiDataset = Template.bind({});
MultiDataset.args = {
  chartData: {
    labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    datasets: [
      { 
        label: 'This Week',
        data: [5, 12, 8, 15, 10, 20, 7],
        backgroundColor: '#4e73df'
      },
      {
        label: 'Last Week',
        data: [3, 8, 10, 12, 8, 15, 5],
        backgroundColor: '#858796'
      }
    ]
  },
};