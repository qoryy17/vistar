<?php

namespace App\Enums;

enum UserRole: string {
    case CUSTOMER = 'Customer';
    case MITRA = 'Mitra';
    case FINANCE = 'Finance';
    case ADMIN = 'Admin';
    case SUPER_ADMIN = 'Superadmin';
}
