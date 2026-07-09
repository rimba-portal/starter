@php
    $calendarId = $calendarId ?? 'calendar-' . uniqid();
@endphp

<div>
    <div wire:ignore id="{{ $calendarId }}"></div>
    @assets
        <script src="{{ asset('js/rrule.min.js') }}"></script>
        <script src="{{ asset('js/calendar.min.js') }}"></script>
        <script src="{{ asset('js/index.global.min.js') }}"></script>
    @endassets

    @script
        <script>
            let calendar; // keep a reference to avoid duplicate inits

            const calendarFunction = () => {

                const calendarId = @js($calendarId);
                const events = @js($events);

                const initCalendar = () => {
                    const calendarEl = document.getElementById(calendarId);

                    if (!calendarEl) {
                        return;
                    }

                    if (calendarEl.dataset.calendarRendered === '1') {
                        return;
                    }

                    calendarEl.dataset.calendarRendered = '1';

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        weekNumbers: true,
                        firstDay: 1,

                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'multiMonthYear,dayGridMonth,timeGridWeek'
                        },

                        height: 650,

                        events: events,

                        eventDidMount: function(info) {
                            info.el.setAttribute('title', info.event.title || '');
                        },
                    });

                    calendar.render();
                };

                setTimeout(initCalendar, 150);

                document.addEventListener('livewire:navigated', () => {
                    setTimeout(initCalendar, 150);
                });
            }();
        </script>
    @endscript
</div>
