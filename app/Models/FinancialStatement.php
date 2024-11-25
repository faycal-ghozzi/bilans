<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = ['company_id', 'entry_point_id', 'date', 'value'];

    // Attribute casting
    protected $casts = [
        'value' => 'decimal:3', // Precision of 15, scale of 3 as per schema
        'date' => 'date', // Ensure the `date` attribute is treated as a date
        'company_id' => 'integer',
        'entry_point_id' => 'integer',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function entryPoint()
    {
        return $this->belongsTo(FsEntryPoint::class, 'entry_point_id');
    }
}
