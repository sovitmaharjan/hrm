<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasStatus extends Model
{
    use HasFactory;

    protected $table = 'model_has_status';
    
    protected $fillable = ['id','model_type', 'model_id', 'status_id'];
}
