<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'phone' => $this->customer->phone,
                'email' => $this->customer->email,
            ],
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'manager_response_date' => $this->manager_response_date,
            'created_at' => $this->created_at,
            'attachments' => method_exists($this, 'getMedia') ? $this->getMedia('attachments')->map(function ($m) {
                return [
                    'id' => $m->id,
                    'file_name' => $m->file_name,
                    'mime_type' => $m->mime_type,
                    'size' => $m->size,
                    'url' => method_exists($m, 'getUrl') ? $m->getUrl() : null,
                ];
            })->values() : [],
        ];
    }
}
