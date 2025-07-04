<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = "tasks";
    protected $fillable = ['title', 'description', 'status'];
    
    // Aquí se define la relacion muchos a muchos con el modelo Person
    public function people()
    {
        return $this->belongsToMany(Person::class, 'person_task'); //se hace la referencia a través de la tabla intermediaria
    }
}


