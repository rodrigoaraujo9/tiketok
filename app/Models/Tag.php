<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    use HasFactory;

    // Nome da tabela associada
    protected $table = 'tags';

    // Chave primária da tabela
    protected $primaryKey = 'tag_id';

    // Campos que podem ser atribuídos em massa
    protected $fillable = [
        'name',
        'event_id',
    ];

    /**
     * Relacionamento: uma tag pertence a um evento.
     */
    // Tag.php
    public function events()
    {
        return $this->hasMany(Event::class, 'tag_id');
    }

}
