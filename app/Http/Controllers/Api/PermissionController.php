<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionController extends ApiController
{
    public function index()
    {
        return response()->json(PermissionResource::collection(Permission::orderBy("id")->get()));
    }
}
