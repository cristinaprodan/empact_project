<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelTable extends Model
{
    use HasFactory;
    protected $fillable=[
        'id',
        'title',
        'link',
        'copyright',
        'pubDate',
        'image_url'
    ];
}
