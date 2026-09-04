<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'livreur_id',
        'pickup_address',
        'pickup_lat',
        'pickup_lng',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'description',
        'price',
        'status',
        'payment_method',
        'payment_reference',
        'payment_status',
    ];

    protected $casts = [
        'pickup_lat' => 'float',
        'pickup_lng' => 'float',
        'delivery_lat' => 'float',
        'delivery_lng' => 'float',
        'price' => 'float',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'order_id');
    }
}
