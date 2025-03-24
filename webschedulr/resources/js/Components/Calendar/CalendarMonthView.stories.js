import CalendarMonthView from './CalendarMonthView.vue';
import CalendarDayCell from './CalendarDayCell.vue';
import AppointmentCard from './AppointmentCard.vue';
import { format, startOfMonth, endOfMonth, startOfWeek, endOfWeek, eachDayOfInterval, isSameMonth, isToday } from 'date-fns';

export default {
  title: 'Calendar/CalendarMonthView',
  component: CalendarMonthView,
  tags: ['autodocs']
};

export const Default = {
  render: (args) => ({
    components: { CalendarMonthView, AppointmentCard },
    setup() {
      const today = new Date();
      const currentMonth = today.getMonth();
      const currentYear = today.getFullYear();
      
      const monthStart = startOfMonth(today);
      const monthEnd = endOfMonth(today);
      const calStart = startOfWeek(monthStart);
      const calEnd = endOfWeek(monthEnd);
      
      const daysArray = eachDayOfInterval({ start: calStart, end: calEnd });
      
      const calendarDays = daysArray.map(date => ({
        date: format(date, 'yyyy-MM-dd'),
        isCurrentMonth: isSameMonth(date, monthStart),
        isToday: isToday(date)
      }));
      
      const appointments = [
        {
          id: 1,
          client: { name: 'John Smith' },
          service: { name: 'Haircut' },
          start_time: format(today, 'yyyy-MM-dd') + 'T09:00:00',
          status: 'confirmed'
        },
        {
          id: 2,
          client: { name: 'Sarah Johnson' },
          service: { name: 'Manicure' },
          start_time: format(today, 'yyyy-MM-dd') + 'T14:30:00',
          status: 'pending'
        }
      ];
      
      function getAppointmentsForDay(dateString) {
        const dateToCheck = new Date(dateString);
        dateToCheck.setHours(0, 0, 0, 0);
        
        return appointments.filter(appointment => {
          const appointmentDate = new Date(appointment.start_time);
          appointmentDate.setHours(0, 0, 0, 0);
          return appointmentDate.getTime() === dateToCheck.getTime();
        });
      }
      
      return { 
        ...args, 
        currentMonth, 
        currentYear, 
        calendarDays,
        getAppointmentsForDay
      };
    },
    template: `
      <div class="p-4 bg-gray-100">
        <CalendarMonthView
          :current-month="currentMonth"
          :current-year="currentYear"
          :calendar-days="calendarDays"
          @add-appointment="date => console.log('Add appointment for', date)"
        >
          <template #default="{ day }">
            <AppointmentCard
              v-for="appointment in getAppointmentsForDay(day.date)"
              :key="appointment.id"
              :appointment="appointment"
              @click="() => console.log('Click appointment', appointment.id)"
            />
          </template>
        </CalendarMonthView>
      </div>
    `
  })
};