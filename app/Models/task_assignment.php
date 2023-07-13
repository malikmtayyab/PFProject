<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task_assignment extends Model
{
    use HasFactory;
    protected $table="task_assignments";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
