<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
class Supplier extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
