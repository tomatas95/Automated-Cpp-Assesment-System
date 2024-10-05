<?php

namespace App\Models;

use App\Services\TranslationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'code',
        'test_result_1',
        'test_result_2',
        'test_result_3',
        'cpu_time',
        'compilation_time',
        'memory_time',
        'auto_check_correct_cases',
];

public function scopeFilter($query, array $filters){
    if($filters['search'] ?? false){
        $search = $filters['search'];
        $locale = session('lang', 'en');

        if ($locale !== 'en') {
            $search = TranslationService::translateTerm($search, $locale);

            $timeLocale = '/^(\d+)\s*(minutės|minučių|minutė|minutę|valandos|valandų|valanda|valandą|dienos|dienų|diena|dieną|dienu|Minutės|Minučių|Minutė|Minutę|Valandos|Valandų|Valanda|Valandą|Dienos|Dienų|Diena|Dieną|Dienu)$/i';
            $searchLocale = '/^(\d+\/\d+)\s+(teisingi|teisingas|teisingai|teisingų|teisinga|teisingu|Teisingi|Teisingas|Teisingai|Teisingų|Teisinga|Teisingu)$/i';
        } else {
            $timeLocale = '/^(\d+)\s*(hours|hour|minutes|minute|days|day)$/i';
            $searchLocale = '/^(\d+\/\d+)\s+correct$/i';
        }
        
        if (preg_match($searchLocale, $search, $matches)) {
            $autoCheckSearch = $matches[1];
        } else {
            $autoCheckSearch = $search;
        }

        if (preg_match($timeLocale, $search, $matches)) {
            $autoCheckTimeSearch = $matches[1];
        } else {
            $autoCheckTimeSearch = $search;
        }

        $query->whereHas('exercise', function($query) use ($search, $autoCheckTimeSearch){
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('time_number', 'like', '%' . $search . '%')
                ->orWhere('time_unit', 'like', '%' . $search . '%')
                ->orWhereRaw("CONCAT(time_number, ' ', time_unit) LIKE ?", ["%{$autoCheckTimeSearch}%"])
                ->orWhere('difficulty', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%');
        })
        ->orWhere('updated_at', 'like', '%' . $search . '%')
        ->orWhere('auto_check_correct_cases', 'like', '%' . $autoCheckSearch . '%');
    }
}

    public function scopeFilterSubmissionView($query, array $filters){
        if($filters['search'] ?? false){
            $search = $filters['search'];
            $locale = session('lang', 'en');

            if ($locale !== 'en') {
                $search = TranslationService::translateTerm($search, $locale);
    
                $searchLocale = '/^(\d+\/\d+)\s+(teisingi|teisingas|teisingai|teisingų|teisinga)$/i';
            } else {
                $searchLocale = '/^(\d+\/\d+)\s+correct$/i';
            }
            
            if (preg_match($searchLocale, $search, $matches)) {
                $autoCheckSearch = $matches[1];
            } else {
                $autoCheckSearch = $search;
            }

            $query->whereHas('user', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhere('auto_check_correct_cases', 'like', '%' . $autoCheckSearch . '%')
            ->orWhere('updated_at', 'like', '%' . $search . '%');
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function exercise(){
        return $this->belongsTo(Exercise::class);
    }
}
