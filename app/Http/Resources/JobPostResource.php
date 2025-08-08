<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
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
            'title' => $this->title,
            'company' => $this->company_name,
            'company_logo' => $this->company_logo,
            'location' => $this->location,
            'category' => $this->category,
            'salary' => $this->salary_range,
            'description' => $this->description,
            'benefits' => $this->benefits,
            'type' => $this->employment_type,
            'work_condition' => $this->work_condition,
            'candidates' => count($this->applications),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
