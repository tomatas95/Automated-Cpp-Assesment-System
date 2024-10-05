<?php

namespace App\Models;

use App\Services\TranslationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'user_id',
        'content', 
        'hint1', 
        'hint2',
        'hint3', 
        'difficulty',
        'allow_automatic_check_view',
        'allow_automatic_check_run',
        'submission_count',
        'time_number',
        'time_unit',
        'code_solution',
        'check1',
        'check1_answer',
        'check2',
        'check2_answer',
        'check3',
        'check3_answer',
        'exercise_visibility',
        'exercise_password'
];

    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $search = $filters['search'];
            $locale = session('lang', 'en');
    
            if ($locale !== 'en') {
                $search = TranslationService::translateTerm($search, $locale);
    
                $timeLocale = '/^(\d+)\s*(minutės|minučių|minutė|minutę|valandos|valandų|valanda|valandą|dienos|dienų|diena|dieną|dienu|Minutės|Minučių|Minutė|Minutę|Valandos|Valandų|Valanda|Valandą|Dienos|Dienų|Diena|Dieną|Dienu)$/i';
            } else {
                $timeLocale = '/^(\d+)\s*(hours|hour|minutes|minute|days|day|Hours|Hour|Minutes|Minute|Days|Day)$/i';
            }
    
            if (preg_match($timeLocale, $search, $matches)) {
                $autoCheckTimeSearch = $matches[1];
            } else {
                $autoCheckTimeSearch = $search;
            }
    
            $query->whereHas('user', function($query) use ($search){
                $query->where('name', 'like', '%' . $search . '%');
            })
                ->orWhere('title', 'like', '%' . $search . '%')
                ->orWhere('time_number', 'like', '%' . $search . '%')
                ->orWhere('time_unit', 'like', '%' . $search . '%')
                ->orWhereRaw("CONCAT(time_number, ' ', time_unit) LIKE ?", ["%{$autoCheckTimeSearch}%"])
                ->orWhere('difficulty', 'like', '%' . $search . '%');
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function submissions(){
        return $this->hasMany(Submission::class, 'user_id');
    }
}
