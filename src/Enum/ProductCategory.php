<?php

namespace App\Enum;

enum ProductCategory: string
{
    case LUNCH_BOX = 'lunch_box';
    case DRINK = 'drink';
    case DESSERT = 'dessert';
    case SNACK = 'snack';
    case OTHER = 'other';
}
