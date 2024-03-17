<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuestionnaireUser extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    
    use HasFactory;

    protected $table = 'questionnaire_users'; 


    protected $fillable = [
        'questionnaire_id',
        'user_id',
        'exam_code',
        'status',
    ];

    public function questionnaire(): BelongsTo 
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }

    public function options(): BelongsToMany 
    {
        return $this->belongsToMany(Option::class, 'answers');
    }
}
