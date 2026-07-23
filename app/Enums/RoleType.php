<?php

namespace App\Enums;

enum RoleType: string
{
    case FATHER = 'father';
    case MOTHER = 'mother';
    case CHILD = 'child';
    case GUARDIAN = 'guardian';
}
