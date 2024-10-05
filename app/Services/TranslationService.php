<?php

namespace App\Services;

use GuzzleHttp\Client;

class TranslationService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.mymemory.translated.net/',
        ]);
    }

    public static function getTranslations(){
        return [
            'difficulty' => [
                'Easy' => ['lt' => 'Lengvas', 'lengvas', 'Lengva', 'lengva', 'lengvas lygis', 'Lengvas Lygis', 'lengvo lygio', 'Lengvo lygio', 'lengvas sudėtingumas', 'Lengvas sudėtingumas'],
                'Normal' => ['lt' => 'Normalus', 'normalus', 'normalu', 'Normalu', 'Normali', 'normali', 'vidutinis', 'Vidutinis', 'normalus lygis', 'Normalus lygis', 'vidutinis lygis', 'Vidutinis lygis', 'normalaus lygio', 'Normalaus lygio', 'normalus sudėtingumas', 'Normalus sudėtingumas'],
                'Hard' => ['lt' => 'Sunkus', 'sunkus', 'sunku', 'sunkus lygis', 'sunkaus lygio', 'sunkus sudėtingumas'],
            ],
            'time_unit' => [
                'minutes' => ['lt' => 'minutės', 'Minutės', 'minutes', 'Minutes', 'minučių', 'Minučių', 'Minučiu', 'minučiu', 'minute', 'Minute', 'minutė', 'Minutė', 'Minutę', 'minutę'],
                'hours' => ['lt' => 'valandos', 'valandų', 'valandu', 'valanda', 'valandą', 'Valandos', 'Valandų', 'Valandu', 'Valanda', 'Valandą'],
                'days' => ['lt' => 'dienos', 'diena', 'dienų', 'dieną', 'Dienos', 'Diena', 'Dieną', 'dienu'],
            ],
            'role' => [
                'guest' => ['lt' => 'svečias', 'Svečias', 'svecias', 'Svecias', 'Svečiai', 'sveciai'],
                'admin' => ['lt' => 'administratorius', 'adminas', 'admin', 'Administratorius', 'administratoriai', 'adminai', 'admins']
            ],
        ];
    }

    public static function translateTerm($term, $locale){
        $translations = self::getTranslations();

        foreach($translations as $field => $terms){
            foreach($terms as $enTerm => $translations){
                if($locale !== 'en' && in_array($term, $translations)){
                    return $enTerm;
                }
            }
        }
        return $term;
    }

    public function translate($text, $targetLanguage = 'lt')
    {
        $response = $this->client->get('get', [
            'query' => [
                'q' => $text,
                'langpair' => 'en|' . $targetLanguage,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['responseData']['translatedText'];
    }
}
