<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'entry_point_id', 'date', 'value'];

    protected $casts = [
        'value' => 'decimal:3', 
        'date' => 'date',
        'company_id' => 'integer',
        'entry_point_id' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function entryPoint()
    {
        return $this->belongsTo(FsEntryPoint::class, 'entry_point_id');
    }
}
