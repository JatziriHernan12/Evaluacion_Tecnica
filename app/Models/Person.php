<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = "people"; //define directamente el nombre de la tabla
    protected $fillable = ['name', 'avatar']; //y permite la asignación masiva
}
