<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    // Status constants
    public const STATUS_DRAFT      = 'draft';
    public const STATUS_PENDING    = 'pending';
    public const STATUS_APPROVED   = 'approved';
    public const STATUS_FUNDING    = 'funding';
    public const STATUS_ACTIVE     = 'active';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_DEFAULTED  = 'defaulted';
    protected $fillable = [
        'borrower_id',
        'category_id',
        'title',
        'summary',
        'description',
        'funding_goal',
        'funded_amount',
        'interest_rate',
        'min_investment',
        'term_months',
        'status',
        'slug',
        'image',
    ];
    protected $casts = [
        'reviewed_at' => 'datetime',
        'pre_approved_at' => 'datetime',
        'open_for_investment_at' => 'datetime',
        'funded_at' => 'datetime',
        'repayment_started_at' => 'datetime',
    ];


    // لتسهيل ترتيب المراحل
    public function stages()
    {
        return [
            'reviewed_at' => 'قيد المراجعة',
            'pre_approved_at' => 'تم الموافقة المبدئية',
            'open_for_investment_at' => 'مفتوح للاستثمار',
            'funded_at' => 'ممول بالكامل',
            'repayment_started_at' => 'في مرحلة السداد',
        ];
    }
    public function canChangeStatus(string $newStatus): bool
    {
        $allowedTransitions = [
            self::STATUS_DRAFT    => [self::STATUS_PENDING],
            self::STATUS_PENDING  => [self::STATUS_APPROVED],
            self::STATUS_APPROVED => [self::STATUS_FUNDING],
            self::STATUS_FUNDING  => [self::STATUS_ACTIVE],
            self::STATUS_ACTIVE   => [self::STATUS_COMPLETED],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }
    public static function stageResetMap(): array
    {
        return [
            self::STATUS_DRAFT => [
                'reviewed_at',
                'pre_approved_at',
                'open_for_investment_at',
                'funded_at',
                'repayment_started_at',
            ],
            self::STATUS_PENDING => [
                'pre_approved_at',
                'open_for_investment_at',
                'funded_at',
                'repayment_started_at',
            ],
            self::STATUS_APPROVED => [
                'open_for_investment_at',
                'funded_at',
                'repayment_started_at',
            ],
            self::STATUS_FUNDING => [
                'funded_at',
                'repayment_started_at',
            ],
            self::STATUS_ACTIVE => [
                'repayment_started_at',
            ],
        ];
    }

    public function scopeSearch($query, $term)
    {
        return $term
            ? $query->where('title', 'like', "%{$term}%")
            : $query;
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function getPercentageAttribute()
    {
        return $this->funding_goal > 0
            ? round(($this->funded_amount / $this->funding_goal) * 100)
            : 0;
    }

    public function getIsCompletedAttribute()
    {
        return $this->funded_amount >= $this->funding_goal;
    }
    public function deleteImages()
    {
        $this->images->each(function ($img) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image);
            $img->delete();
        });

        if ($this->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->image);
        }
    }
}
