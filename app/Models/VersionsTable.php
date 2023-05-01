<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionsTable extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'version',
    ];
}
