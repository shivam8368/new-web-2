<?php

/*
 |  It is not recommended to create custom php files inside public directory and avoiding laravel fromework,
 |  however this is understandable exception. This file only parse search query, loop through arrays and
 |  return suggestions for search input. No laravel Booting and Application needed here, this solution is
 |  much faster and consume much less performance.
 */

use JetBrains\PhpStorm\ArrayShape;

class SearchSuggestions {
    public function Invoke()
    {
        if(!isset($_POST['query']) || !is_string($_POST['query'])) {
            exit;
        }

        $output = [];
        $suggestions = static::GetSearchSuggestions($_POST['query']);
        $finishedWords = join(' ', $suggestions['finishedWords']);

        foreach($suggestions['suggestions'] as $suggestion) {
            $output[] = (!empty($finishedWords) ? $finishedWords . ' ' : '') . $suggestion;
        }

        header('Content-Type: application/json');
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }

    #[ArrayShape(['finishedWords' => "array", 'suggestions' => "array|array[]"])]
    private static function GetSearchSuggestions(string $query): array
    {
        $queryWords = array_filter(array_slice(explode(' ', $query), 0, 24));
        $searchableWords = static::GetSearchableWords();

        $finishedWords = [];
        if(count($queryWords) > 1) {
            foreach($queryWords as $queryWord) {
                $queryWordLower = mb_strtolower($queryWord);

                if(array_key_exists($queryWordLower, $searchableWords['multiWordsSuggestions']) || in_array($queryWordLower, $searchableWords['singleWordsSuggestions'])) {
                    $finishedWords[] = "<span class='sugg-highlighted'>{$queryWord}</span>";
                }else{
                    $finishedWords[] = $queryWord;
                }
            }

            array_pop($finishedWords);
        }

        $suggestions = [
            'singleWordsStartsWith' => [],
            'singleWordsContains' => [],
            'multiWordsStartsWith' => [],
            'multiWordsContains' => []
        ];

        $lastQueryWord = end($queryWords);
        $lastQueryWordLower = mb_strtolower($lastQueryWord);
        $lastButOneQueryWord = prev($queryWords);
        $lastButOneQueryWordLower = mb_strtolower($lastButOneQueryWord);

        if($lastButOneQueryWord === false || !array_key_exists($lastButOneQueryWordLower, $searchableWords['multiWordsSuggestions'])) {
            foreach($searchableWords['singleWordsSuggestions'] as $word) {
                if (str_starts_with($word, $lastQueryWordLower)) {
                    $suggestions['singleWordsStartsWith'][] = "<span class='sugg-highlighted'>{$lastQueryWord}</span>" . mb_substr($word, mb_strlen($lastQueryWord));
                }else if(str_contains($word, $lastQueryWordLower)) {
                    $suggestions['singleWordsContains'][] = static::replaceFirst($lastQueryWordLower, "<span class='sugg-highlighted'>{$lastQueryWord}</span>", $word);
                }
            }

            foreach($searchableWords['multiWordsSuggestions'] as $word => $secondWords) {
                if (str_starts_with($word, $lastQueryWordLower)) {
                    array_unshift($suggestions['multiWordsStartsWith'], "<span class='sugg-highlighted'>{$lastQueryWord}</span>" . mb_substr($word, mb_strlen($lastQueryWord)));

                    foreach($secondWords as $secondWord) {
                        $suggestions['multiWordsStartsWith'][] = "<span class='sugg-highlighted'>{$lastQueryWord}</span>" . mb_substr($word, mb_strlen($lastQueryWord)) . ' ' . $secondWord;

                    }
                }else if(str_contains($word, $lastQueryWordLower)) {
                    foreach($secondWords as $secondWord) {
                        $suggestions['multiWordsContains'][] = static::replaceFirst($lastQueryWordLower, "<span class='sugg-highlighted'>{$lastQueryWord}</span>", $word . ' ' . $secondWord);
                    }
                }
            }
        }else{
            foreach($searchableWords['multiWordsSuggestions'][$lastButOneQueryWordLower] as $secondWord) {
                if(str_starts_with($secondWord, $lastQueryWordLower)) {
                    $suggestions['multiWordsStartsWith'][] = static::replaceFirst($lastQueryWordLower, "<span class='sugg-highlighted'>{$lastQueryWord}</span>", $secondWord);
                }else if(str_contains($secondWord, $lastQueryWordLower)) {
                    $suggestions['multiWordsContains'][] = static::replaceFirst($lastQueryWordLower, "<span class='sugg-highlighted'>{$lastQueryWord}</span>", $secondWord);
                }
            }
        }

        return [
            'finishedWords' => $finishedWords,
            'suggestions' => array_merge(
                array_slice($suggestions['singleWordsStartsWith'], 0, 12),
                array_slice($suggestions['multiWordsStartsWith'], 0, 12),
                array_slice($suggestions['singleWordsContains'], 0, 6),
                array_slice($suggestions['multiWordsContains'], 0, 6)
            )
        ];
    }

    /**
     *  List of words for search whisperer when user is taping in search bar
     */
    #[ArrayShape(['singleWordsSuggestions' => "string[]", 'multiWordsSuggestions' => "string[]"])]
    private static function GetSearchableWords(): array
    {
        $multiWordsSuggestions = [
            'small' => [
                'tits',
                'teen',
                'dick',
                'pussy',
                'ass',
                'boobs'
            ],

            'double' => [
                'penetration',
                'anal',
                'blowjob',
                'creampie',
            ],

            'big' => [
                'dick',
                'tits',
                'boobs',
                'ass',
            ],

            'solo' => [
                'girl',
                'male',
            ],

            'role' => [
                'play',
            ],

            'vaginal' => [
                'masturbation',
                'sex',
                'creampie'
            ],

            'female' => [
                'agent',
                'orgasm',
                'masturbation',
            ],

            'anal' => [
                'masturbation',
                'fingering',
                'teen',
                'mature',
                'creampie'
            ],

            'reverse' => [
                'cowgirl',
            ],

            'fake' => [
                'taxi',
                'tits',
                'boobs',
                'hostel',
                'agent'
            ],

            'face' => [
                'fuck',
                'sitting',
            ],

            'cuckold' => [
                '',
                'cleanup',
                'creampie',
                'husband',
            ],

            'natural' => [
                'tits',
                'boobs',
            ],

            'virtual' => [
                'reality',
                'sex',
            ],

            'pussy' => [
                'licking',
            ],

            'doggy' => [
                '',
                'style',
                'pov',
                'sex'
            ],

            'ass' => [
                'licking',
                'masturbation',
                'fingering',
                'creampie'
            ],

            'lesbian' => [
                '',
                'sex',
                'scissoring',
                'massage',
                'milf',
                'bondage'
            ]
        ];

        $singleWordsSuggestions = [
            'scissoring',
            'doggystyle',
            'solomale',
            'sologirl',
            'roleplay',
            'facefuck',
            'facesitting',
            'piss',
            'pissing',
            'sex',
            'fuck',
            'creampie',
            'cumshot',
            'threesome',
            'cosplay',
            'fisting',
            'schoolgirl',
            'public',
            'redhead',
            'facials',
            'chubby',
            '69',
            'amateur',
            'mature',
            'kissing',
            'feet',
            'bondage',
            'brunette',
            'orgasm',
            'german',
            'fetish',
            'gangbang',
            'teens',
            'latina',
            'blonde',
            'fingering',
            'milf',
            'european',
            'missionary',
            'group',
            'interracial',
            'japanese',
            'orgy',
            'blowjob',
            'lingerie',
            'ebony',
            'romantic',
            'toys',
            'massage',
            'masturbation',
            'pov',
            'asian',
            'trimmed',
            'deepthroat',
            'hardcore',
            'compilation',
            'squirting',
            'cowgirl',
            'bukkake',
            'casting',
            'swallow',
            'couple',
            'rimming',
            'homemade',
            'outdoor',
            'oral',
            'licking',
            'petite',
            'skinny',
            'handjob',
            'titfuck'
        ];

        return [
            'singleWordsSuggestions' => $singleWordsSuggestions,
            'multiWordsSuggestions' => $multiWordsSuggestions
        ];
    }

    private static function replaceFirst($search, $replace, $subject)
    {
        $search = (string) $search;

        if ($search === '') {
            return $subject;
        }

        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }
}

(new SearchSuggestions())->Invoke();
