<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','service_id','invoice_number','total_price','status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
