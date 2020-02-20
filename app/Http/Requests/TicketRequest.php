<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $current_time=\Carbon\Carbon::now()->format('Y-m-d');

        return [
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'deadline' => 'date_format:Y-m-d|after_or_equal:'.$current_time,
            'assign_id' => 'required|numeric|exists:users,id',
        ];
    }
}
