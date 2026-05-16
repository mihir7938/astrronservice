<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolutionStage extends Model
{
    use HasFactory;

    protected $table = 'solution_stages';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function complains()
    {
        return $this->hasMany(Complain::class, 'solution_id', 'id');
    }
}
