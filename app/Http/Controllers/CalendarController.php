<?php

namespace App\Http\Controllers;

use App\Models\EquipmentLoan;
use App\Models\Event;
use App\Models\Meeting;
use App\Models\Venue;

class CalendarController extends Controller
{
    public $sources = [
        [
            'model'      => Event::class,
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'name',
            'prefix'     => 'Evento:',
            'color'      => '#6366F1',
            'route'      => 'admin.events.edit',
        ],
        [
            'model'      => Meeting::class,
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'attendees',
            'prefix'     => 'Reunión:',
            'color'      => '#10B981',
            'route'      => 'admin.meetings.edit',
        ],
        [
            'model'      => EquipmentLoan::class,
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'borrower_name',
            'prefix'     => 'Préstamo:',
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

            if (request('venue_id') && $modelClass === Event::class) {
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

        return view('Calendar', compact('calendarEvents', 'venues'));
    }
}
