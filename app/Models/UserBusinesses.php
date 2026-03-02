<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBusinesses extends Model
{
    use HasFactory;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'user_businesses';

    public function user_business_branches()
    {
        return $this->hasMany(UserBusinessBranches::class, 'user_id', 'id');
    }
}
