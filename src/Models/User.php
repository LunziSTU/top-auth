<?php

namespace Lunzi\TopAuth\Models;

use think\Model;
use Lunzi\TopAuth\Authenticatable;
use Lunzi\TopAuth\Contracts\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'users';
}