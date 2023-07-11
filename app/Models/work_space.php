<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class work_space extends Model
{
    use HasFactory;
    protected $table="work_space";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
