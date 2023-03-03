<?php

namespace App;

class Consts
{
    // Url
    const URL_API = "http://127.0.0.1:8000";

    // Paginate
    const PAGE = 20;

    // Password
    const PASSWORD_DEFAULT = "bridge1";

    // Role
    const ADMIN = 3;
    const MENTOR = 2;
    const STUDENT = 1;

    // Question
    const TEXT_QUESTION = 1;
    const ILLUSTRATED_QUESTION = 2;
    const IMAGE_QUESTION = 3;
    const QUESTION_2D = 4;
    const QUESTION_360 = 5;
    const OX = 6;

    // Active
    const INACTIVE = 0;
    const ACTIVE = 1;

    // Illustration
    const ILLUSTRATION_GOOD = 1;
    const ILLUSTRATION_EXCELLENT = 2;

    //
    const SCHOOL_TYPE = 1;
    const GROUP_TYPE = 2;

    // Account Progress
    const TYPE_TRAINING = 1;
    const TYPE_VIDEO = 2;
    const TYPE_TRAINING_RANDOM = 3;

    const STATUS_DOING = 0;
    const STATUS_DONE = 1;
    // Date month school year
    const TIME_START_YEAR = "04-01";
    const TIME_END_YEAR = "03-31";
    const START_YEAR = "01-01";
    //code
    const ADMIN_CODE = "ADM";

    const LEVEL_DEFAULT = 0;
    const LEVEL_MIN = 10;
    const LEVEL_MAX = 1;
}
