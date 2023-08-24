<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'event_google_id' => $this->event_google_id,
            'summary' => $this->summary,
            'title' => $this->summary,
            'start' => $this->start,
            'end' => $this->end,
            'calendar_type_id' => $this->calendar_type_id,
            'link' => $this->link,
            'public' => $this->public,
            'location' => $this->location,
            'description' => $this->description,
            'charges' => $this->charges->pluck('id'),
            'color' => $this->calendar_type?->color,
            'guest' => $this->users->pluck("id")->contains(auth()->user()->id),
        ];
    }
}
