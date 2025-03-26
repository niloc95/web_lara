import SimpleBarChart from './SimpleBarChart.vue';

export default {
  title: 'Charts/SimpleBarChart',
  component: SimpleBarChart,
  argTypes: {
    title: { control: 'text' },
    subtitle: { control: 'text' },
    bordered: { control: 'boolean' },
    height: { control: 'text' },
    loading: { control: 'boolean' },
    noDataText: { control: 'text' },
    wrapperClass: { control: 'text' },
    chartData: { control: 'object' },
    options: { control: 'object' },
    colorScheme: { 
      control: { type: 'select' }, 
      options: ['vibrant', 'pastel', 'monochrome', 'github']
    },
    showUtilization: { control: 'boolean' },
    utilizationData: { control: 'object' },
    workingHoursStart: { control: 'number' },
    workingHoursEnd: { control: 'number' },
    showWorkingHoursSummary: { control: 'boolean' }
  },
  parameters: {
    docs: {
      description: {
        component: 'An enhanced bar chart component that includes working hour utilization metrics and vibrant color schemes.'
      }
    }
  }
};

// Sample chart data
const defaultChartData = {
  labels: ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'],
  datasets: [
    {
      label: 'Appointments',
      data: [1, 3, 2, 4, 0, 2, 3, 1],
      borderRadius: 4
    }
  ]
};

// Sample utilization data (hours booked per hour of the day)
const utilizationData = {
  '8': 0.5, // 30 min of appointments at 8:00
  '9': 1.0, // 1 hour of appointments at 9:00 
  '10': 0.5,
  '11': 1.0,
  '12': 0,  // No appointments during lunch
  '13': 0.5,
  '14': 1.0,
  '15': 0.5
};

export const Default = {
  args: {
    chartData: defaultChartData,
    title: 'Daily Appointment Schedule',
    subtitle: 'Hours booked throughout the day',
    bordered: true,
    height: '300px',
    loading: false,
    colorScheme: 'vibrant'
  }
};

export const WithUtilization = {
  args: {
    ...Default.args,
    showUtilization: true,
    utilizationData: utilizationData,
    workingHoursStart: 8,
    workingHoursEnd: 16,
    showWorkingHoursSummary: true
  }
};

export const PastelColorScheme = {
  args: {
    ...WithUtilization.args,
    colorScheme: 'pastel',
    title: 'Appointment Schedule (Pastel)',
  }
};

export const MonochromeColorScheme = {
  args: {
    ...WithUtilization.args,
    colorScheme: 'monochrome',
    title: 'Appointment Schedule (Monochrome)',
  }
};

export const MultipleDataSeries = {
  args: {
    ...Default.args,
    title: 'Appointment Types',
    subtitle: 'Distribution by type throughout the day',
    chartData: {
      labels: ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'],
      datasets: [
        {
          label: 'Regular',
          data: [1, 2, 1, 2, 0, 1, 2, 1],
          borderRadius: 4
        },
        {
          label: 'Priority',
          data: [0, 1, 1, 2, 0, 1, 1, 0],
          borderRadius: 4
        }
      ]
    },
    showUtilization: true,
    utilizationData: {
      '8': 0.5,
      '9': 1.5, 
      '10': 1.0,
      '11': 2.0,
      '12': 0,
      '13': 1.0,
      '14': 1.5,
      '15': 0.5
    },
    showWorkingHoursSummary: true
  }
};

export const HighUtilization = {
  args: {
    ...Default.args,
    title: 'High Utilization Day',
    subtitle: 'Busy schedule with limited availability',
    utilizationData: {
      '8': 1.0,
      '9': 1.0, 
      '10': 1.0,
      '11': 1.0,
      '12': 0.5,
      '13': 1.0,
      '14': 1.0,
      '15': 1.0
    },
    showUtilization: true,
    showWorkingHoursSummary: true
  }
};

export const Loading = {
  args: {
    ...Default.args,
    loading: true
  }
};

export const NoData = {
  args: {
    ...Default.args,
    chartData: {
      labels: ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'],
      datasets: [
        {
          label: 'Appointments',
          data: [0, 0, 0, 0, 0, 0, 0, 0],
        }
      ]
    },
    noDataText: 'No appointments scheduled for this day'
  }
};