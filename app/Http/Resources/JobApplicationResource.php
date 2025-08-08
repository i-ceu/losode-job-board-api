<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class JobApplicationResource extends JsonResource
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
            'first_name' => $this->first_name,
            'Last_name' => $this->Last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'Location' => $this->location,
            'cv_path' => $this->cv_path ? url(Storage::url($this->cv_path)) : null
        ];
    }
}
