<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->firstname . ' ' . $this->lastname,
            'first_name' => $this->firstname ?? '',
            'last_name' => $this->lastname ?? '',
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->image ? $this->image : '',
            'status' => $this->status == 1 ? 'active' : 'inactive'
        ];
    }
}
