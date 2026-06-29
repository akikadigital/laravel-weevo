<?php

use Akika\LaravelWeevo\Enums\DeliveryStatus;

it('returns the correct label for each status', function () {
    expect(DeliveryStatus::Pending->label())->toBe('Pending')
        ->and(DeliveryStatus::Assigned->label())->toBe('Assigned')
        ->and(DeliveryStatus::Picked->label())->toBe('Picked')
        ->and(DeliveryStatus::InTransit->label())->toBe('In Transit')
        ->and(DeliveryStatus::DeliveryInitiated->label())->toBe('Delivery Initiated')
        ->and(DeliveryStatus::PaymentRequested->label())->toBe('Payment Requested')
        ->and(DeliveryStatus::Delivered->label())->toBe('Delivered')
        ->and(DeliveryStatus::Failed->label())->toBe('Failed')
        ->and(DeliveryStatus::Cancelled->label())->toBe('Cancelled')
        ->and(DeliveryStatus::Returning->label())->toBe('Returning')
        ->and(DeliveryStatus::Returned->label())->toBe('Returned');
});

it('returns all delivery status options', function () {
    expect(DeliveryStatus::options())->toBe([
        'pending' => 'Pending',
        'assigned' => 'Assigned',
        'picked' => 'Picked',
        'in_transit' => 'In Transit',
        'delivery_initiated' => 'Delivery Initiated',
        'payment_requested' => 'Payment Requested',
        'delivered' => 'Delivered',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled',
        'returning' => 'Returning',
        'returned' => 'Returned',
    ]);
});

it('identifies final statuses using enum instances', function () {
    expect(DeliveryStatus::isFinal(DeliveryStatus::Delivered))->toBeTrue()
        ->and(DeliveryStatus::isFinal(DeliveryStatus::Failed))->toBeTrue()
        ->and(DeliveryStatus::isFinal(DeliveryStatus::Cancelled))->toBeTrue()
        ->and(DeliveryStatus::isFinal(DeliveryStatus::Returned))->toBeTrue()
        ->and(DeliveryStatus::isFinal(DeliveryStatus::Pending))->toBeFalse()
        ->and(DeliveryStatus::isFinal(DeliveryStatus::Assigned))->toBeFalse();
});

it('identifies final statuses using strings', function () {
    expect(DeliveryStatus::isFinal('delivered'))->toBeTrue()
        ->and(DeliveryStatus::isFinal('failed'))->toBeTrue()
        ->and(DeliveryStatus::isFinal('cancelled'))->toBeTrue()
        ->and(DeliveryStatus::isFinal('returned'))->toBeTrue()
        ->and(DeliveryStatus::isFinal('pending'))->toBeFalse()
        ->and(DeliveryStatus::isFinal('assigned'))->toBeFalse();
});

it('returns final status values', function () {
    expect(DeliveryStatus::finalValues())->toBe([
        'delivered',
        'failed',
        'cancelled',
        'returned',
    ]);
});

it('returns final status cases', function () {
    expect(DeliveryStatus::finalCases())->toBe([
        DeliveryStatus::Delivered,
        DeliveryStatus::Failed,
        DeliveryStatus::Cancelled,
        DeliveryStatus::Returned,
    ]);
});

it('returns return status values', function () {
    expect(DeliveryStatus::returnValues())->toBe([
        'in_transit',
        'returning',
    ]);
});

it('returns badge colors correctly', function () {
    expect(DeliveryStatus::badgeColor(DeliveryStatus::Delivered))->toBe('bg-success')
        ->and(DeliveryStatus::badgeColor(DeliveryStatus::Failed))->toBe('bg-danger')
        ->and(DeliveryStatus::badgeColor(DeliveryStatus::Cancelled))->toBe('bg-danger')
        ->and(DeliveryStatus::badgeColor(DeliveryStatus::Returned))->toBe('bg-danger')
        ->and(DeliveryStatus::badgeColor(DeliveryStatus::InTransit))->toBe('bg-primary')
        ->and(DeliveryStatus::badgeColor(DeliveryStatus::Pending))->toBe('bg-info');
});

it('returns badge colors correctly using strings', function () {
    expect(DeliveryStatus::badgeColor('delivered'))->toBe('bg-success')
        ->and(DeliveryStatus::badgeColor('failed'))->toBe('bg-danger')
        ->and(DeliveryStatus::badgeColor('cancelled'))->toBe('bg-danger')
        ->and(DeliveryStatus::badgeColor('returned'))->toBe('bg-danger')
        ->and(DeliveryStatus::badgeColor('in_transit'))->toBe('bg-primary')
        ->and(DeliveryStatus::badgeColor('pending'))->toBe('bg-info');
});

it('returns correct workflow step counters', function () {
    expect(DeliveryStatus::stepCounter(DeliveryStatus::Pending))->toBe(1)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Assigned))->toBe(2)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Picked))->toBe(3)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::InTransit))->toBe(4)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::DeliveryInitiated))->toBe(4)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::PaymentRequested))->toBe(4)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Returning))->toBe(4)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Delivered))->toBe(5)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Failed))->toBe(5)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Cancelled))->toBe(5)
        ->and(DeliveryStatus::stepCounter(DeliveryStatus::Returned))->toBe(5);
});

it('returns zero for unknown status', function () {
    expect(DeliveryStatus::stepCounter('unknown'))->toBe(0)
        ->and(DeliveryStatus::badgeColor('unknown'))->toBe('bg-info')
        ->and(DeliveryStatus::isFinal('unknown'))->toBeFalse();
});
