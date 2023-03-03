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
        'required' => 'field is required.',
    ],
    'unique' => [
        'studentID' => 'The studentID field already exists.'
    ],
    'distinct' => [
        'code' => 'The code field has a duplicate value.'
    ],
    'format' => [
        'StudentID' => "Student ID : does not true format",
        'FacultyID' => 'The Faculty ID : does not true format',
        'SchoolID/GroupID_Student' => 'SchoolID/GroupID and StudentID does not match',
    ],
    'exist' => [
        'SchoolID/GroupID' => "SchoolID/GroupID : does not exist",
        'FacultyID' => 'FacultyID: does not exist',
        'FacultyID_SchoolID' => 'FacultyID does not in School',
    ],
    'date' => [
        'ExpirationDate' => 'Expiration Date: must be after now',
        'Birthday' => 'Birthday: must be before now',
        'errorDate' => 'errorDate',
    ],
    'wrong' => [
        'QuestionType' => 'Question Type is not exist',
        'Subject' => 'Subject is not exist',
        'media' => 'File is not exist',
        'public' => 'public is TRUE/FALSE',
        'CategoryQuestion' => 'Category Question is not exist',
        'FacultyID' => 'FacultyID is not in SCHOOLID',
    ],
    'between' => [
        'Level' => 'The Level must be 1 2 3.',
    ],
    'correct' => [
        'true/false' => '正解は真か偽でなければなりません',
        '4option' => '正解はに存在しません４選択肢',
    ]
];
