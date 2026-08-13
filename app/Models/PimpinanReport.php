<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PimpinanReport extends Model
{
    protected $fillable = ['job_posting_id', 'report_title', 'pdf_path', 'status'];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }
}
