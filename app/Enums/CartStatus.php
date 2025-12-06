<?php
namespace App\Enums;

enum CartStatus: int {
    case Active = 1;
    case CheckedOut = 2;
}
