<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case VALIDATED = 'validated';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';
}
