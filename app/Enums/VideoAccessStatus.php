<?php
namespace App\Enums;

enum VideoAccessStatus: int {
    case Active = 1;
    case Blocked = 0;
    case Expired = 2;
}