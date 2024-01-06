<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use Searchable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poolingAccountPortfolio()
    {
        return $this->belongsTo(PoolingAccountPortfolio::class, 'pooling_account_id', 'id');
    }
}
