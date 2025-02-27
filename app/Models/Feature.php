<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','price','service_id'];
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
}
