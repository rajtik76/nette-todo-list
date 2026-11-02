<?php declare(strict_types=1);

use App\Model\Todo\Todo;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('defaults to incomplete with current timestamp', function () {
	$before = time();
	$todo = new Todo(1, 'Buy milk');

	Assert::same(1, $todo->getId());
	Assert::same('Buy milk', $todo->getText());
	Assert::false($todo->isCompleted());
	Assert::true($todo->getCreatedAt() >= $before && $todo->getCreatedAt() <= time());
});


test('keeps explicit timestamp', function () {
	$todo = new Todo(1, 'Buy milk', true, 1_700_000_000);

	Assert::true($todo->isCompleted());
	Assert::same(1_700_000_000, $todo->getCreatedAt());
});


test('toggle flips completion both ways', function () {
	$todo = new Todo(1, 'Buy milk');

	$todo->toggle();
	Assert::true($todo->isCompleted());

	$todo->toggle();
	Assert::false($todo->isCompleted());
});
