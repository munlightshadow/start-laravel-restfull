<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Review;
use App\Models\Subscription;

use App\Http\Resources\Avatar as AvatarResource;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\Review as ReviewResource;
use App\Http\Resources\Subscription as SubscriptionResource;


class UserFull extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $tastings = Subscription::rightjoin('tastings', 'tastings.id', '=', 'subscriptions.instance_id')
                                ->select('tastings.*')
                                ->where('subscriptions.user_id', '=', $this->id)
                                ->where('subscriptions.type', '=', 'TASTING')
                                ->get(); 

        $clubs = Subscription::rightjoin('clubs', 'clubs.id', '=', 'subscriptions.instance_id')
                                ->select('clubs.*')
                                ->where('subscriptions.user_id', '=', $this->id)
                                ->where('subscriptions.type', '=', 'CLUB')
                                ->get();

        $institutions = Subscription::rightjoin('institutions', 'institutions.id', '=', 'subscriptions.instance_id')
                                ->select('institutions.*')
                                ->where('subscriptions.user_id', '=', $this->id)
                                ->where('subscriptions.type', '=', 'INSTITUTION')
                                ->get();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => new AvatarResource($this->file),
            'roles' => RoleResource::collection($this->roles),
            'tastings_subscription' => $tastings,
            'clubs_subscription' => $clubs,
            'institutions_subscription' => $institutions,
            'review' => new ReviewResource($this->reviews()->first()),
            'review_count' => $this->reviews()->count(),
        ];
    }
}
