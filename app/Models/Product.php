<?php

namespace App\Models;

use App\Utopia\Traits\Filterable;
use App\Utopia\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Uuid, Filterable;

    protected $fillable = ['name', 'description', 'code', 'status'];
}
