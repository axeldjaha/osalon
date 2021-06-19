<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permissions = new Collection();
        Permission::orderBy("id")->each(function ($permission) use (&$permissions){
            $permission->granted = $this->hasPermissionTo($permission->id);
            $permissions->add($permission);
        });

        return [
            "id" => $this->id,
            "name" => $this->name,
            "telephone" => $this->telephone,
            "email" => $this->email,
            "salon_id" => $this->salon_id,
            "permissions" => PermissionResource::collection($permissions),
        ];
    }
}
