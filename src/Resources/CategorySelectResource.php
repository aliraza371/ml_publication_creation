<?php

namespace Aliraza371\MlPublicationCreation\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Created by PhpStorm.
 * User: aliraza
 * Date: 24/01/2022
 * Time: 11:58 AM
 */
class CategorySelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->mla_category_id,
            'text' => $this->name
        ];
    }
}
