<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->id,
            'company' => $this->name,
            'about' => $this->about,
            'company_url' => $this->company_url,
            'ceo' => $this->ceo,
            'ceo_contact' => $this->ceo_contact,
            'cto_contact' => $this->cto_contact,
            'cto_name' => $this->cto_name,
            'hr' => $this->hr,
            'hr' => $this->hr_contact,
            'logo' => $this->logo,

            'stack_be_plang' => $this->plangs->map(function ($item) {
                return [$item->name => ['rating' => $item->pivot->rating]];
            }),
            'stack_be_framework' => $this->frameworks->map(function ($item) {
                return [$item->name => ['rating' => $item->pivot->rating]];
            }),
        ];
    }
}
