<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckoutDetail extends Model
{
    use HasFactory;

    protected $table = 'checkout_details';

    protected $fillable = [
        'user_id',
        'transaction_reference',
        'billing_name',
        'billing_address',
        'billing_city',
        'billing_zip',
        'billing_country',
        'shipping_method',
        'card_name',
        'card_number',
        'expiration_date',
        'cvv',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
