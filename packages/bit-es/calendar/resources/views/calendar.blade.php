<div class="space-y-6 p-1">
    {{-- FullCalendar --}}
    <x-filament::section>
        <div wire:ignore id="calendar" class="min-h-[600px] w-full"></div>
    </x-filament::section>

    {{-- Events Table --}}
    {{ $this->table }}
</div>

@assets
    <script src="{{ asset('js/rrule.min.js') }}"></script>
    <script src="{{ asset('js/calendar.min.js') }}"></script>
    <script src="{{ asset('js/index.global.min.js') }}"></script>
@endassets

@script
<script>
    let calendar;

    const initCalendar = () => {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        if (calendar) {
            calendar.destroy();
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            weekNumbers: true,
            firstDay: 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'multiMonthYear,dayGridMonth,timeGridWeek'
            },
            height: 'auto',
            events: JSON.parse(@js($this->events)),
            eventDidMount: info => info.el.setAttribute('title', info.event.title || ''),
        });

        calendar.render();
    };

    document.addEventListener('DOMContentLoaded', initCalendar);
    document.addEventListener('livewire:navigated', initCalendar);
    document.addEventListener('filament:modal-opened', initCalendar);
    document.addEventListener('filament:modal-loaded', initCalendar);
</script>
@endscript