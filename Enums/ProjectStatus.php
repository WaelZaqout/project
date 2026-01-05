<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Draft      = 'draft';
    case Pending    = 'pending';
    case Approved   = 'approved';
    case Funding    = 'funding';
    case Active     = 'active';
    case Completed  = 'completed';
    case Defaulted  = 'defaulted';

    public function label(): string
    {
        return match ($this) {
            self::Draft      => 'Draft',
            self::Pending    => 'Pending Review',
            self::Approved   => 'Pre-Approved',
            self::Funding    => 'Open for Funding',
            self::Active     => 'Active',
            self::Completed  => 'Completed',
            self::Defaulted  => 'Defaulted',
        };
    }
}
