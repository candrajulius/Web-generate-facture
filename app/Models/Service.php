<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    use HasFactory;
    protected $fillable = ['name','description'];

    public function features()
    {
        return $this->hasMany(Feature::class,'feature_id');
    }

    public function createInvoice($user_id)
    {
        $invoice = Invoice::create([
            'customer_id' => $user_id,
            'service_id' => $this->id,
            'invoice_number' => 'INV-' . time(),
            'total_price' => collect($this->features)->sum('price'),
            'status' => 'pending',
        ]);

        foreach($this->features as $feature)
        {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => $feature['name'],
                'description' => $feature['description'],
                'price' => $feature['price'],
            ]);
        }
    }
}
