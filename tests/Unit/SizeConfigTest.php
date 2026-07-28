<?php

it('includes adult and kids shoe size options from 38 to 47', function () {
    $sizes = require __DIR__ . '/../../config/sizes.php';

    expect($sizes['adult_shoe_options'])->toBe(['38', '39', '40', '41', '42', '43', '44', '45', '46', '47'])
        ->and($sizes['kids_shoe_options'])->toBe(['18', '19', '20', '21', '22', '23', '24', '25', '26', '27',
            '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47'])
        ->and($sizes['shoe_options'])->toBe(['38', '39', '40', '41', '42', '43', '44', '45', '46', '47']);
});
