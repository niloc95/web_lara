// resources/js/Components/Layout/TopHeader.stories.js
import TopHeader from './TopHeader.vue';

export default {
  title: 'Layout/TopHeader',
  component: TopHeader,
  parameters: {
    layout: 'fullscreen',
  },
  argTypes: {
    companyName: { control: 'text' },
    logoUrl: { control: 'text' },
    user: { control: 'object' }
  }
};

const Template = (args) => ({
  components: { TopHeader },
  setup() {
    return { args };
  },
  template: '<TopHeader v-bind="args" />'
});

export const Default = Template.bind({});
Default.args = {
  companyName: 'WebSchedulr',
  logoUrl: null,
  user: {
    name: 'John Doe',
    email: 'john@example.com',
    avatar: null
  }
};

export const WithUserAvatar = Template.bind({});
WithUserAvatar.args = {
  ...Default.args,
  user: {
    name: 'John Doe',
    email: 'john@example.com',
    avatar: 'https://i.pravatar.cc/300'
  }
};