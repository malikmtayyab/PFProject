<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invitation_table extends Model
{
    use HasFactory;
    protected $table = 'invitation_tables';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
