<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\LineItem;

class Quote extends Model
{
    use HasFactory;

    public function lineItems(){
        return $this->hasMany(LineItem::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function scopeGetAmount(){
        return $this->lineItems->pluck('amount')->sum() + $this->delivery;
    }

    public function scopeGetVAT(){
        return $this->lineItems->pluck('vat')->sum();
    }

    public function scopeGetDiscValue(){
        return $this->lineItems->pluck('disc')->sum();
    }

    public function scopeGetDiscount(){
        return $this->lineItems->pluck('discount')->sum();
    }

    public function scopeGetSubTotal(){
        $vat = $this->lineItems->pluck('vat')->sum();
        $total = $this->lineItems->pluck('amount')->sum();

        return $total - $vat;
    }
}