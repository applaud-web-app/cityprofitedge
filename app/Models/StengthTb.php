<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StengthTb extends Model
{
    use HasFactory;
    protected $table = "strength_tb";

    protected $fillable = [
        'symbol_name',
        'atm',
        'timeframe',
        'iv',
        'delta',
        'theta',
        'vega',
        'gamma',
        'strength',
        'ce_symbol',
        'pe_symbol'
    ];
}
