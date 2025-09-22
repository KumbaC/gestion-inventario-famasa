<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Type_coin as TypeCoin;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class Box extends Model 
{
    use HasFactory;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function typeCoin()
    {
        return $this->belongsTo(TypeCoin::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
