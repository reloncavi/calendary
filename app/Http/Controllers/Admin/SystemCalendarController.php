<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;

class SystemCalendarController extends Controller
{
    public $sources = [
        [
            'model'      => \App\Models\Event::class,
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'name',
            'prefix'     => 'Evento:',
            'suffix'     => '',
            'color'      => '#6366F1',
            'route'      => 'admin.events.edit',
        ],
        [
            'model'      => \App\Models\Meeting::class,
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'attendees',
            'prefix'     => 'Reunión:',
            'suffix'     => '',
            'color'      => '#10B981',
            'route'      => 'admin.meetings.edit',
        ],
        [
            'model'      => \App\Models\EquipmentLoan::class,
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'borrower_name',
            'prefix'     => 'Préstamo:',
            'suffix'     => '',
            'color'      => '#F59E0B',
            'route'      => 'admin.equipment-loans.edit',
        ],
    ];

    public function index()
    {
        $calendarEvents = [];
        $venues = Venue::all();

        foreach ($this->sources as $source) {
            $modelClass = $source['model'];

            $query = $modelClass::query();

            // Filter by venue only for Event model
            if (request('venue_id') && $modelClass === \App\Models\Event::class) {
                $query->where('venue_id', request('venue_id'));
            }

            foreach ($query->get() as $model) {
                $start = $model->getOriginal($source['start_time']);
                $end   = $model->getOriginal($source['end_time']);

                if (!$start) {
                    continue;
                }

                $calendarEvents[] = [
                    'title'           => trim($source['prefix'] . ' ' . $model->{$source['field']}),
                    'start'           => $start,
                    'end'             => $end,
                    'url'             => route($source['route'], $model->id),
                    'backgroundColor' => $source['color'],
                    'borderColor'     => $source['color'],
                ];
            }
        }

        return view('admin.calendar.calendar', compact('calendarEvents', 'venues'));
    }
}
