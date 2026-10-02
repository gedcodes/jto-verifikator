<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailPelanggaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function __construct($status, $message, $resource, $meta = [])
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
        $this->meta = $meta;
    }


    public function toArray($request)
    {
        return [
            'success'   => $this->status,
            'message'   => $this->message,
            'data'      => $this->resource,
            'meta'      => $this->meta
        ];
        // return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'kode' => $this->kode,
        //     'nama' => $this->nama,
        //     'is_active' => $this->is_active,
        //     'created_at' => (string) $this->created_at,
        //     'updated_at' => (string) $this->updated_at,
        // ];
    }
}
