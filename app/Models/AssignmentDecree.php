<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentDecree extends Model
{
    protected $table = 'assignment_decrees';
    protected $primaryKey = 'id_assignment_decree';
    protected $fillable = ['no_sk', 'date_sk', 'no_ba_verval', 'date_ba_verval'];
}