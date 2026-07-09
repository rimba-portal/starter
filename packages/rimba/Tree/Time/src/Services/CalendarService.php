<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Rimba\Tree\Time\Models\Event;
use Rimba\Tree\Time\Support\ShiftPattern;

class CalendarService
{
    public function getEventsForUser(?Authenticatable $user): Collection
    {
        $publicEvents = $this->getPublicEvents();

        $shiftEvents = $this->getShiftEvents($user);

        return $shiftEvents
            ->concat($publicEvents)
            ->values();
    }

    protected function getPublicEvents(): Collection
    {
        return Event::query()
            ->where('status', 'active')
            ->get()
            ->map(function (Event $event): array {
                return [
                    'title' => $event->title,
                    'start' => $event->starts_at?->toDateString(),
                    'end' => $event->ends_at?->toDateString(),
                    'allDay' => $event->is_all_day,
                    'color' => $event->event_color,
                ];
            });
    }

    protected function getShiftEvents(?Authenticatable $user): Collection
    {
        $scode = $user?->staff?->shiftCode;

        if (blank($scode)) {
            return collect();
        }

        [$shiftGroup] = explode('-', $scode, 2);

        foreach (array_keys(config('shift_pattern.patterns', [])) as $key) {
            $pattern = ShiftPattern::fromConfig($key);

            if (! $pattern->hasTeam($shiftGroup)) {
                continue;
            }

            $tz = config(
                sprintf('shift_pattern.patterns.%s.timezone', $key),
                config('app.timezone')
            );

            $now = Carbon::now($tz);

            return collect(
                $pattern->eventsForTeamInRange(
                    $shiftGroup,
                    $now->copy()->startOfMonth()->subDays(7),
                    $now->copy()->endOfMonth()->addDays(7),
                )
            );
        }

        return collect();
    }
}
