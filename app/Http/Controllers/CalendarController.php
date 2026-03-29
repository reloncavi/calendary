<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Venue;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public $sources = [
        [
            'model'      => '\\App\\Event',
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'name',
            'prefix'     => 'Evento',
            'suffix'     => '',
            'route'      => 'admin.events.edit',
            'color'      => '#006cb7',
        ],
        [
            'model'      => '\\App\\Meeting',
            'start_time' => 'start_time',
            'end_time'   => 'end_time',
            'field'      => 'attendees',
            'prefix'     => 'Reunión con',
            'suffix'     => '',
            'route'      => 'admin.meetings.edit',
            'color'      => '#28a745',
        ],
    ];

    public function index()
    {
        $events = [];

        $venues = Venue::all();

        foreach ($this->sources as $source) {
            $calendarEvents = $source['model']::when(request('venue_id') && $source['model'] == '\App\Event', function($query) {
                return $query->where('venue_id', request('venue_id'));
            })->get();
            foreach ($calendarEvents as $model) {
                $start_time = $model->getOriginal($source['start_time']);

                $end_time = $model->getOriginal($source['end_time']);

                if (!$start_time) {
                    continue;
                }

                $events[] = [
                    'title' => trim($source['prefix'] . " " . $model->{$source['field']}
                        . " " . $source['suffix']),
                    'start' => $start_time,
                    'end' => $end_time,
                    'url'   => route('admin.events.update', $model->id),
                    'color' => $source['color'],
                ];
            }
        }

        return view('Calendar', compact('events', 'venues'));
    }
}
