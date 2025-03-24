// resources/js/Components/UI/Tooltip.stories.js
import Tooltip from './Tooltip.vue';

export default {
  title: 'UI/Tooltip',
  component: Tooltip,
  parameters: {
    layout: 'centered',
  },
  argTypes: {
    text: { control: 'text' },
    position: { 
      control: { type: 'select' },
      options: ['top', 'bottom', 'left', 'right'] 
    }
  }
};

const Template = (args) => ({
  components: { Tooltip },
  setup() {
    return { args };
  },
  template: `
    <div class="p-24 flex items-center justify-center">
      <Tooltip v-bind="args">
        <button class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600">
          Hover me
        </button>
      </Tooltip>
    </div>
  `
});

export const Default = Template.bind({});
Default.args = {
  text: 'This is a tooltip',
  position: 'top'
};

export const Bottom = Template.bind({});
Bottom.args = {
  text: 'Tooltip at the bottom',
  position: 'bottom'
};

export const Left = Template.bind({});
Left.args = {
  text: 'Tooltip on the left',
  position: 'left'
};

export const Right = Template.bind({});
Right.args = {
  text: 'Tooltip on the right',
  position: 'right'
};

export const LongContent = Template.bind({});
LongContent.args = {
  text: 'This tooltip has a longer text content to demonstrate wrapping',
  position: 'top'
};

// Show tooltip with an icon
export const WithIcon = (args) => ({
  components: { Tooltip },
  setup() {
    return { args };
  },
  template: `
    <div class="p-24 flex items-center justify-center">
      <Tooltip text="Information tooltip">
        <button class="h-8 w-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
          <i class="fas fa-info"></i>
        </button>
      </Tooltip>
    </div>
  `
});