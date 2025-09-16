<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use App\Models\Client;
use App\Models\Box;
use App\Models\Position;
use App\Models\Type_coin as TypeCoin;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;


class Sale extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function typeCoin(): BelongsTo
    {
        return $this->belongsTo(TypeCoin::class);
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'inventory_sale')->withPivot('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
