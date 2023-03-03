<?php

namespace App\Repositories;

use App\Consts;
use Illuminate\Support\Facades\Redis;

class FuriganaRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct()
    {

    }

    public function furigana($questions)
    {
        try {
            if (count($questions) === 0) {
                return $questions;
            }
            foreach ($questions as $key => $question) {
                if ($question['check_furigana'] === 1) {
                    $questions[$key]['content'] = $question['content_furigana'];
                    $questions[$key]['answer1'] = $question['answer1_furigana'];
                    $questions[$key]['answer2'] = $question['answer2_furigana'];
                    $questions[$key]['answer3'] = $question['answer3_furigana'];
                    $questions[$key]['answer4'] = $question['answer4_furigana'];
                }
                unset(
                    $questions[$key]['check_furigana'],
                    $questions[$key]['content_furigana'],
                    $questions[$key]['answer1_furigana'],
                    $questions[$key]['answer2_furigana'],
                    $questions[$key]['answer3_furigana'],
                    $questions[$key]['answer4_furigana']
                );
            }
            return $questions;
        }
        catch (\Exception $e) {
            return $questions;
        }
    }

    public function analysisDataQuestions($questions)
    {
        foreach ($questions as $key => $question) {
            $questions[$key] = $this->analysisQuestion($question);
        }
        return $questions;
    }

    public function analysisQuestion($question)
    {
        $content = $question['content'];
        $answer1 = $question['answer1'];
        $answer2 = $question['answer2'];
        $answer3 = $question['answer3'];
        $answer4 = $question['answer4'];
        if ($content) {
            $question['content'] = $this->analysisText($content);
        }
        if ($answer1) {
            $question['answer1'] = $this->analysisText($answer1);
        }
        if ($answer2) {
            $question['answer2'] = $this->analysisText($answer2);
        }
        if ($answer3) {
            $question['answer3'] = $this->analysisText($answer3);
        }
        if ($answer4) {
            $question['answer4'] = $this->analysisText($answer4);
        }
        return $question;
    }

    public function analysisText($text): string
    {
        try {
            $array_text = explode(" ", $text);
            foreach ($array_text as $key => $value) {
                // for cases like string associated with the character Ex: text, text. +text text@
                $preg_text = preg_split('/(\.\.\.\s?|[-.?!+@%,&^$#_=|*;~:(){}\[\]\'"]\s?)|\s/', $value, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

                $content = "";
                foreach ($preg_text as $preg_value) {
                    $furiganaCharacter = $this->analysisFurigana($preg_value);
                    if ($furiganaCharacter !== $preg_value) {
                        $content .= $furiganaCharacter;
                    } else if (strlen($furiganaCharacter) > 1) {
                        foreach (mb_str_split($furiganaCharacter, 1) as $value2) {
                            $furiganaCharacter2 = $this->analysisFurigana($value2);
                            $content .= $furiganaCharacter2;
                        }
                    } else {
                        $content .= $furiganaCharacter;
                    }
                }
                $array_text[$key] = $content;
            }
            $text_white_space = (implode(" ", $array_text));

            return str_replace('~', '<br/>', $text_white_space);
        } catch (\Exception $exception) {
            return $text;
        }
    }

    public function analysisFurigana($furigana)
    {
        $text_furigana = Redis::get($furigana);
        if ($text_furigana) {
            $text_furigana = json_decode($text_furigana);
            $content = "";
            foreach ($text_furigana as $key => $value) {
                $value = (array)$value;
                if (count($value) == 2) {
                    $tag_ruby = "<ruby>" . $value['ruby'];
                    $tag_rt = "<rt>" . $value['rt'] . "</rt></ruby>";
                    $content .= $tag_ruby . $tag_rt;
                }
                else {
                    $tag_ruby = "<ruby>" . $value['ruby'] . "</ruby>";
                    $content .= $tag_ruby;
                }
            }
            $furigana = $content;
        }
        return $furigana;
    }
}