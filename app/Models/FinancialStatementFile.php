<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialStatementFile extends Model
{
    protected $fillable = [
        'company_id',
        'file_path',
        'currency',
        'date'
    ];

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }}
