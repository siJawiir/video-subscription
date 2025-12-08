<?php
namespace App\Enums;

enum VideoAccessStatus: int {
    case Blocked = 0;
    case Active = 1;
    case Expired = 2;
}