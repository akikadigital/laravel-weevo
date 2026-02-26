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
        return match ($this) {
            self::Pending => 'Pending',
            self::Assigned => 'Assigned',
            self::Picked => 'Picked',
            self::InTransit => 'In Transit',
            self::DeliveryInitiated => 'Delivery Initiated',
            self::PaymentRequested => 'Payment Requested',
            self::Delivered => 'Delivered',
            self::Failed => 'Failed',
            self::Cancelled => 'Cancelled',
            self::Returning => 'Returning',
            self::Returned => 'Returned',
        };
    }

    public static function getDeliveryStatuses()
    {
        return [
            self::Pending->value => self::Pending->label(),
            self::Assigned->value => self::Assigned->label(),
            self::Picked->value => self::Picked->label(),
            self::InTransit->value => self::InTransit->label(),
            self::DeliveryInitiated->value => self::DeliveryInitiated->label(),
            self::PaymentRequested->value => self::PaymentRequested->label(),
            self::Delivered->value => self::Delivered->label(),
            self::Failed->value => self::Failed->label(),
            self::Cancelled->value => self::Cancelled->label(),
            self::Returning->value => self::Returning->label(),
            self::Returned->value => self::Returned->label(),
        ];
    }

    /**
     * Check if the delivery status is final.
     *
     * @return bool
     */

    public static function isFinal(string $status): bool
    {
        return in_array($status, [
            self::Delivered,
            self::Failed,
            self::Cancelled,
            self::Returned,
        ]);
    }

    public static function getFinalStateStatuses(): array
    {
        return [
            self::Delivered,
            self::Failed,
            self::Cancelled,
            self::Returned,
        ];
    }

    public static function getBadgeColorByStatus($status): string
    {
        return match ($status) {
            self::Delivered->value => 'bg-success',
            self::Failed->value => 'bg-danger',
            self::Cancelled->value => 'bg-danger',
            self::Returned->value => 'bg-danger',
            self::InTransit->value => 'bg-primary',
            default => 'bg-info',
        };
    }

    public static function fromValue(string $value = ''): ?self
    {
        return self::tryFrom($value);
    }

    public static function getReturnStatuses(): array
    {
        return [
            self::InTransit,
            self::Returning,
        ];
    }

    public static function getStepCounter($status): int
    {
        return match ($status) {
            self::Pending->value => 1,
            self::Assigned->value => 2,
            self::Picked->value => 3,
            self::InTransit->value => 4,
            self::DeliveryInitiated->value => 4,
            self::PaymentRequested->value => 4,
            self::Returning->value => 4,
            self::Delivered->value => 5,
            self::Failed->value => 5,
            self::Cancelled->value => 5,
            self::Returned->value => 5,
            default => 0,
        };
    }
}
