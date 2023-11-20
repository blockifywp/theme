<?php

declare(strict_types=1);

namespace Blockify\Theme\Tests;

use function Blockify\Theme\str_between;

test('str_between returns string between two strings', function () {
	$string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

	$result = str_between('Lorem', 'elit', $string,);
	expect($result)->toBe('Lorem ipsum dolor sit amet, consectetur adipiscing elit');

	$result = str_between('Lorem', 'elit', $string, true);
	expect($result)->toBe(' ipsum dolor sit amet, consectetur adipiscing ');

	$result = str_between('Nonexistent', 'String', $string);
	expect($result)->toBe('');

	$result = str_between('Nonexistent', 'String', $string, true);
	expect($result)->toBe('');
});

test('str_between returns all occurrences when $all is true', function () {
	$string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem tempus dolor sit amet, consectetur fermentum elit.';

	$result = str_between('Lorem', 'elit', $string, false, true);
	expect($result)->toBeArray();
	expect($result)->toEqual(['Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem tempus dolor sit amet, consectetur fermentum elit']);

	$result = str_between('Lorem', 'elit', $string, true, true);
	expect($result)->toBeArray();
	expect($result)->toEqual([' ipsum dolor sit amet, consectetur adipiscing ', ' tempus dolor sit amet, consectetur fermentum ']);

	$result = str_between('dolor', 'elit', $string, false, true);
	expect($result)->toBeArray();
	expect($result)->toEqual(['dolor sit amet, consectetur adipiscing elit', 'dolor sit amet, consectetur fermentum elit']);

	$result = str_between('dolor', 'elit', $string, true, true);
	expect($result)->toBeArray();
	expect($result)->toEqual([' sit amet, consectetur adipiscing ', ' sit amet, consectetur fermentum ']);
});

test('str_between returns empty string or array when no match found', function () {
	$string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

	$result = str_between('Nonexistent', 'String', $string);
	expect($result)->toBe('');

	$result = str_between('Nonexistent', 'String', $string, true);
	expect($result)->toBe('');

	$result = str_between('Nonexistent', 'String', $string, false, true);
	expect($result)->toBeArray()->toBe([]);

	$result = str_between('Nonexistent', 'String', $string, true, true);
	expect($result)->toBeArray()->toBe([]);
});

