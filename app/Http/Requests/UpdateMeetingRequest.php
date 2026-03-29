<?php

namespace App\Http\Requests;

use App\Models\Meeting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('meeting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'attendees'  => ['required', 'string', 'max:255'],
            'start_time' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'end_time' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'after:start_time',
            ],
        ];
    }
}
