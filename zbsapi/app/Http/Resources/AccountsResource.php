<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'date_entered' => \DateTime::createFromFormat('Y-m-d H:i:s',$this->date_entered)->format('d M Y H:i:s'),
            'transaction_name' => $this->transaction_name,
            'funeral_id' => $this->funeral_id,
            'amount' => $this->amount,
        ];
    }
}
