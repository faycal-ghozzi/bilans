<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

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
        return $this->belongsTo(Company::class);
    }}
