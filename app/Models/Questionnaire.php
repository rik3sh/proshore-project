<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'expiry_date'];

    public function questions(): BelongsToMany 
    {
        return $this->belongsToMany(Question::class);
    }

    public function users(): BelongsToMany 
    {
        return $this->belongsToMany(User::class, 'questionnaire_users');
    }

    /**
     * Scope a query to only include not expired questionnaires.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('expiry_date', '>', date('Y-m-d'));
    } 
}
