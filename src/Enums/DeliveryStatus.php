<?php

namespace Akika\LaravelWeevo\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Assigned = 'assigned';
    case Picked = 'picked';
    case InTransit = 'in_transit';
    case DeliveryInitiated = 'delivery_initiated';
    case PaymentRequested = 'payment_requested';
    case Delivered = 'delivered';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case Returning = 'returning';
    case Returned = 'returned';

    public function label(): string
    {
        return str($this->value)->replace('_', ' ')->title()->toString();
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $status) => [
                $status->value => $status->label(),
            ])
            ->toArray();
    }

    public static function isFinal(string|self $status): bool
    {
        $value = $status instanceof self ? $status->value : $status;

        return in_array($value, self::finalValues(), true);
    }

    public static function finalValues(): array
    {
        return [
            self::Delivered->value,
            self::Failed->value,
            self::Cancelled->value,
            self::Returned->value,
        ];
    }

    public static function finalCases(): array
    {
        return [
            self::Delivered,
            self::Failed,
            self::Cancelled,
            self::Returned,
        ];
    }

    public static function returnValues(): array
    {
        return [
            self::InTransit->value,
            self::Returning->value,
        ];
    }

    public static function badgeColor(string|self $status): string
    {
        $value = $status instanceof self ? $status->value : $status;

        return match ($value) {
            self::Delivered->value => 'bg-success',
            self::Failed->value,
            self::Cancelled->value,
            self::Returned->value => 'bg-danger',
            self::InTransit->value => 'bg-primary',
            default => 'bg-info',
        };
    }

    public static function stepCounter(string|self $status): int
    {
        $value = $status instanceof self ? $status->value : $status;

        return match ($value) {
            self::Pending->value => 1,
            self::Assigned->value => 2,
            self::Picked->value => 3,
            self::InTransit->value,
            self::DeliveryInitiated->value,
            self::PaymentRequested->value,
            self::Returning->value => 4,
            self::Delivered->value,
            self::Failed->value,
            self::Cancelled->value,
            self::Returned->value => 5,
            default => 0,
        };
    }
}
