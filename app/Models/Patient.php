<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;
    // Optioneel: soft deletes voor veilige verwijdering/herstel
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'birth_date',
        'contact',
    ];

    // Casts voor consistente types (PSR-12 geformatteerd)
    protected $casts = [
        'birth_date' => 'date',
    ];
}