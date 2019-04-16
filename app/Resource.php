<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Resource extends Model
{
    protected $fillable = ['filename', 'mime', 'size', 'title'];
}
