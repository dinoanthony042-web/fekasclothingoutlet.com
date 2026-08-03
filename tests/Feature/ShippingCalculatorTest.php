<?php

use App\Services\ShippingCalculator;

describe('shipping calculator', function () {
    it('charges no delivery fee for addresses within Abuja', function () {
        $calculator = app(ShippingCalculator::class);

        $result = $calculator->calculateShipping(
            city: 'Abuja',
            state: 'Federal Capital Territory',
            country: 'Nigeria',
            address: 'No 5, Gwarinpa, Abuja'
        );

        expect($result['amount'])->toBe(0)
            ->and($result['zone'])->toBe('abuja');
    });

    it('charges the fixed delivery fee outside Abuja', function () {
        $calculator = app(ShippingCalculator::class);

        $result = $calculator->calculateShipping(
            city: 'Lagos',
            state: 'Lagos',
            country: 'Nigeria',
            address: 'No 12, Lekki Phase 1'
        );

        expect($result['amount'])->toBe(9500)
            ->and($result['zone'])->toBe('outside_abuja');
    });
});
