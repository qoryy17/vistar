<?php

namespace App\Enums;

enum UserRole: string {
    case CUSTOMER = 'Customer';
    case MITRA = 'Mitra';
    case SUPER_ADMIN = 'Superadmin';
}
