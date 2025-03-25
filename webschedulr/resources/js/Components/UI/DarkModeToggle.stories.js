// resources/js/Components/UI/DarkModeToggle.stories.js
import DarkModeToggle from './DarkModeToggle.vue';

export default {
  title: 'UI/DarkModeToggle',
  component: DarkModeToggle,
  parameters: {
    layout: 'centered',
  }
};

const Template = (args) => ({
  components: { DarkModeToggle },
  setup() {
    return { args };
  },
  template: `
    <div class="p-8 flex items-center justify-center transition-colors" :class="darkModeClass">
      <DarkModeToggle v-bind="args" />
    </div>
  `,
  data() {
    return {
      darkModeClass: ''
    };
  },
  mounted() {
    // Listen for class changes on document.documentElement
    const observer = new MutationObserver(mutations => {
      mutations.forEach(mutation => {
        if (mutation.attributeName === 'class') {
          this.darkModeClass = document.documentElement.classList.contains('dark') 
            ? 'bg-neutral-800 text-white' 
            : 'bg-white text-black';
        }
      });
    });
    
    observer.observe(document.documentElement, { attributes: true });
    
    // Initial state
    this.darkModeClass = document.documentElement.classList.contains('dark') 
      ? 'bg-neutral-800 text-white' 
      : 'bg-white text-black';
  }
});

export const Default = Template.bind({});
Default.args = {};