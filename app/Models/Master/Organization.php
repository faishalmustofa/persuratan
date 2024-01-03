<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $table = 'organization';
    protected $guarded = [];
}
