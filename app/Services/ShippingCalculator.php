<?php

namespace App\Services;

class ShippingCalculator
{
    protected array $abujaLocations = [
        'hillview-estate-zone-life-camp' => ['label' => 'Hillview Estate Zone (Life Camp)', 'fee' => 2900],
        'gwarinpa' => ['label' => 'Gwarinpa', 'fee' => 2900],
        'dutse' => ['label' => 'Dutse', 'fee' => 4900],
        'apo' => ['label' => 'Apo', 'fee' => 3300],
        'kubwa' => ['label' => 'Kubwa', 'fee' => 5900],
        'galadima' => ['label' => 'Galadima', 'fee' => 3900],
        'maitama' => ['label' => 'Maitama', 'fee' => 2400],
        'guzape' => ['label' => 'Guzape', 'fee' => 2900],
        'asokoro' => ['label' => 'Asokoro', 'fee' => 4900],
        'kado' => ['label' => 'Kado', 'fee' => 2900],
        'utako' => ['label' => 'Utako', 'fee' => 2400],
        'berger' => ['label' => 'Berger', 'fee' => 2400],
        'garki' => ['label' => 'Garki', 'fee' => 2900],
        'lugbe' => ['label' => 'Lugbe', 'fee' => 3900],
        'wuse' => ['label' => 'Wuse', 'fee' => 2900],
        'jabi' => ['label' => 'Jabi', 'fee' => 2400],
        'bwari' => ['label' => 'Bwari', 'fee' => 5900],
        'dowaki' => ['label' => 'Dowaki', 'fee' => 5900],
        'mpape' => ['label' => 'Mpape', 'fee' => 2900],
        'deidei' => ['label' => 'Deidei', 'fee' => 7400],
    ];

    public function calculateShipping(string $city = '', string $state = '', string $country = '', string $address = '', string $deliveryZone = 'outside_abuja', string $abujaLocation = ''): array
    {
        if ($deliveryZone === 'within_abuja') {
            $location = strtolower(trim($abujaLocation));
            if ($location !== '' && isset($this->abujaLocations[$location])) {
                $chosenLocation = $this->abujaLocations[$location];

                return [
                    'amount' => $chosenLocation['fee'],
                    'zone' => 'abuja',
                    'label' => 'Within Abuja - ' . $chosenLocation['label'],
                ];
            }

            return [
                'amount' => 1500,
                'zone' => 'abuja',
                'label' => 'Within Abuja',
            ];
        }

        return [
            'amount' => 9500,
            'zone' => 'outside_abuja',
            'label' => 'Outside Abuja',
        ];
    }
}
