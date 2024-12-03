<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'min_duration_days',
        'max_duration_days',
        'annual_limit',
        'advance_notice_days',
        'requires_justification',
        'required_documents',
        'is_paid',
    ];

}
