<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OperateurResource;
use App\Http\Resources\ProfilResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    protected $user;
    protected $salon;
    protected $compte;

    public function __construct(Request $request)
    {
        $this->user = auth("api")->user();

        $this->compte = $this->user->compte ?? null;

        if($this->user != null)
        {
            $this->salon = $this->user->salons()->where("id", $request->salon)->first();
        }
    }

}
