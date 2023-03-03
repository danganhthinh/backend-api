<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'required' => [
        'required' => '必須フィールドです。',
    ],
    'unique' => [
        'studentID' => 'ユーザーIDは既に存在します。'
    ],
    'distinct' => [
        'code' => 'ユーザー ID の形式が正しくありません。'
    ],
    'format' => [
        'StudentID' => "ユーザー ID の形式が正しくありません。",
        'FacultyID' => '学科ID の形式が正しくありません。',
        'SchoolID/GroupID_Student' => 'SchoolID/GroupID と StudentID 一致しない',
    ],
    'exist' => [
        'SchoolID/GroupID' => "学校ID・団体IDが存在しません。",
        'FacultyID' => '学科IDが存在しません。',
        'FacultyID_SchoolID' => '入力された学校IDに学科IDが含まれていません。',
    ],
    'date' => [
        'ExpirationDate' => '過去の日付を有効期限に使用することはできません。',
        'Birthday' => '未来の日付を生年月日に使用することはできません。',
        'errorDate' => '日付の形式が正しくありません。',
    ],
    'wrong' => [
        'QuestionType' => '設問形式が存在しません。',
        'Subject' => '科目はシステムに存在しません。',
        'media' => 'メディアファイルが見つかりません。',
        'public' => '公開設定は TRUE または FALSE でなければなりません。',
        'CategoryQuestion' => '設問の種類が存在しません。',
        'FacultyID' => 'FacultyID is not in SCHOOLID',
    ],
    'between' => [
        'Level' => 'レベルは1、2、3でなければなりません。',
    ],
    'correct' => [
        'true/false' => '回答は TRUE または FALSE でなければなりません。',
        '4option' => '4つの選択肢に正解がありません。',
    ]
];
