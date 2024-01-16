<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drugs extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'scientificName',
        'tradeName',
        'classification',
        'companyName',
        'quantity',
        'expires_at',
        'price',
    ];

    public function categories(){
        return $this->belongsTo(Categories::class,'category_id','id');
    }
}
