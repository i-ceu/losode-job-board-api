<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'company_name',
        'location',
        'employment_type',
        'salary_range',
        'description',
        'submission_deadline',
        'category',
        'benefits',
        'work_condition',
        'user_id',
    ];

    protected $casts = [
        'submission_deadline' => 'date',
    ];

    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->id)) {
                $job->id = self::generateJobId();
            }
        });
    }

    public static function generateJobId()
    {
        do {
            $id = 'FJB-' . rand(100000, 999999) . '-' . strtoupper(Str::random(2));
        } while (self::where('id', $id)->exists());

        return $id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'ilike', "%{$search}%")
                ->orWhere('company_name', 'ilike', "%{$search}%")
                ->orWhere('location', 'ilike', "%{$search}%")
                ->orWhere('description', 'ilike', "%{$search}%");
        });
    }
}
