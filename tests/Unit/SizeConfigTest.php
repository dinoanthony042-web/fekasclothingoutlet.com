<?php

it('includes adult and kids shoe size options from 38 to 44', function () {
    $sizes = require __DIR__ . '/../../config/sizes.php';

    expect($sizes['adult_shoe_options'])->toBe(['38', '39', '40', '41', '42', '43', '44'])
        ->and($sizes['kids_shoe_options'])->toBe(['38', '39', '40', '41', '42', '43', '44'])
        ->and($sizes['shoe_options'])->toBe(['38', '39', '40', '41', '42', '43', '44']);
});
