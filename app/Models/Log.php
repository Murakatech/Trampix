<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'timestamp',
        'details',
    ];

    // Um Log pode pertencer a um User (relacionamento N:1, user_id pode ser nulo)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
