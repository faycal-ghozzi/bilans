<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FsEntryPoint extends Model
{
    use HasFactory;

    public function financialStatements(){
        return $this->hasMany(FinancialStatement::class);
    }
}
