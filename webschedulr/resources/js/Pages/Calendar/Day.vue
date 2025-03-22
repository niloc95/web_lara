<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { format, parseISO, addDays, subDays } from 'date-fns';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionDropdown from '@/Components/Calendar/ActionDropdown.vue';
import AppointmentModal from '@/Components/Calendar/AppointmentModal.vue';

const props = defineProps({
    date: String,
    appointments: Array,
    clients: Array,
    services: Array,
});

// State variables
const showModal = ref(false);
const selectedAppointment = ref(null);
const selectedHour = ref(null);
const activeDropdown = ref(null);
const hours = ref(Array.from({ length: 13 }, (_, i) => i + 8)); // 8am-8pm

// Computed properties
const currentDate = computed(() => parseISO(props.date));
const formattedDate = computed(() => format(currentDate.value, 'EEEE, MMMM d, yyyy'));
const previousDay = computed(() => format(subDays(currentDate.value, 1), 'yyyy-MM-dd'));
const nextDay = computed(() => format(addDays(currentDate.value, 1), 'yyyy-MM-dd'));
const today = computed(() => format(new Date(), 'yyyy-MM-dd'));

// Appointment methods
function getAppointmentsForHour(hour) {
    return props.appointments.filter(appt => {
        const apptTime = parseISO(appt.start_time);
        return apptTime.getHours() === hour;
    });
}

function getAppointmentStyle(appointment) {
    const startTime = parseISO(appointment.start_time);
    const endTime = parseISO(appointment.end_time);
    const durationMinutes = (endTime - startTime) / (1000 * 60);
    const heightRem = (durationMinutes / 60) * 4; // 4rem per hour
    
    return {
        height: `${heightRem}rem`,
        top: `${(startTime.getMinutes() / 60) * 4}rem`,
        borderLeftColor: appointment.service?.color || '#3498db'
    };
}

function getStatusClass(status) {
    const classes = {
        'pending': 'bg-yellow-100',
        'confirmed': 'bg-green-100',
        'completed': 'bg-blue-100',
        'cancelled': 'bg-red-100'
    };
    return classes[status] || 'bg-gray-100';
}

// UI interaction methods
function formatHour(hour) {
    return `${hour}:00`;
}

function formatTime(dateTime) {
    return format(parseISO(dateTime), 'HH:mm');
}

function openAppointment(appointment) {
    selectedAppointment.value = appointment;
    showModal.value = true;
    activeDropdown.value = null;
}

function createAppointment(hour) {
    selectedAppointment.value = null;
    selectedHour.value = hour;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedAppointment.value = null;
    selectedHour.value = null;
}

function onAppointmentSaved() {
    closeModal();
    router.reload();
}

function toggleDropdown(id) {
    activeDropdown.value = activeDropdown.value === id ? null : id;
}

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('click', (event) => {
        if (activeDropdown.value !== null && 
            !event.target.closest('.dropdown-menu') && 
            !event.target.closest('.dropdown-toggle')) {
            activeDropdown.value = null;
        }
    });
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

onMounted(() => {
  // Redirect to the main calendar
  router.visit(route('calendar'));
});
</script>

<template>
    <AppLayout title="Daily Calendar">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-semibold mb-6">Daily Calendar</h1>
                    
                    <!-- Redirect to the main calendar view -->
                    <p>Redirecting to main calendar...</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.day-schedule {
    min-height: 700px;
}

.appointment {
    transition: all 0.2s ease;
    overflow: hidden;
}

.appointment:hover {
    transform: translateX(2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>