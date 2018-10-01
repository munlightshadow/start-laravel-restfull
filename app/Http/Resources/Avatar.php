<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\S3Helper;

class Avatar extends JsonResource
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
            'id' => $this->id,
            's3name' => $this->s3name,
            'avatarURL' => S3Helper::getURLbyS3Name($this->s3name),
        ];
    }
}
