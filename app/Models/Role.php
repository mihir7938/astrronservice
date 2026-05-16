<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Authenticatable
{
    use HasFactory;
    protected $table = 'roles';

    public const ADMIN_ROLE_ID = 1;
    public const SERVICE_ROLE_ID = 2;
    public const USER_ROLE_ID = 3;
}