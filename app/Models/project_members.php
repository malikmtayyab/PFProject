<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_members extends Model
{
    use HasFactory;
    protected $table="project_members";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
