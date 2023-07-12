<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workspace_admins extends Model
{
    use HasFactory;
    protected $table="workspace_admins";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
