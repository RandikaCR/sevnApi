<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContacts extends Model
{
    use HasFactory;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'user_contacts';

    public function user_contact_bank_account_details()
    {
        return $this->hasMany(UserContactBankAccountDetails::class, 'user_contact_id', 'id');
    }
}
