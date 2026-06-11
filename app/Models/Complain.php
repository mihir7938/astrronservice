<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complain extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'complains';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complain_number',
        'user_id',
        'assign_id',
        'contact_name',
        'contact_number',
        'complain_issue_id',
        'company_name',
        'company_address',
        'estimation_cost',
        'solution_id',
        'message',
        'complain_date',
        'complain_video',
        'bill',
        'followup_remarks_1',
        'followup_date_1',
        'followup_remarks_2',
        'followup_date_2',
        'followup_remarks_3',
        'followup_date_3',
        'followup_remarks_4',
        'followup_date_4',
        'followup_remarks_5',
        'followup_date_5',
        'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assign()
    {
        return $this->belongsTo(User::class, 'assign_id', 'id');
    }

    public function issue()
    {
        return $this->belongsTo(ComplainIssue::class, 'complain_issue_id', 'id');
    }

    public function solution()
    {
        return $this->belongsTo(SolutionStage::class, 'solution_id', 'id');
    }

    public function issueProducts()
    {
        return $this->hasMany(ComplainIssueProduct::class, 'complain_id', 'id');
    }

    public function receiveProducts()
    {
        return $this->hasMany(ComplainReceiveProduct::class, 'complain_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(ComplainPhoto::class, 'complain_id', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
