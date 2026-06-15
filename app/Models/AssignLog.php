<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignLog extends Model
{
    use HasFactory;

    protected $table = 'assign_log';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complain_id',
        'user_id',
        'assign_from',
        'assign_to',
        'date',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assignFrom()
    {
        return $this->belongsTo(User::class, 'assign_from', 'id');
    }

    public function assignTo()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }
}
