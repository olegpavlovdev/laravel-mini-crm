<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true; // public widget
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'files.*' => 'file|max:10240',
        ];
    }
}
