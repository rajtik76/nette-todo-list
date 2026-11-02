<?php

declare(strict_types=1);

use App\Model\Todo\Todo;
use App\Model\Todo\TodoFacade;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function createFacade(): TodoFacade
{
	return createContainer()->getByType(TodoFacade::class);
}


test('starts empty', function () {
	Assert::same([], createFacade()->findAll());
});


test('creates todo', function () {
	$facade = createFacade();
	$todo = $facade->createTodo('Buy milk');

	Assert::type(Todo::class, $todo);
	Assert::same('Buy milk', $todo->getText());
	Assert::false($todo->isCompleted());
	Assert::count(1, $facade->findAll());
});


test('lists newest first', function () {
	$facade = createFacade();
	$facade->createTodo('first');
	$facade->createTodo('second');
	$facade->createTodo('third');

	$texts = array_map(fn(Todo $todo) => $todo->getText(), $facade->findAll());
	Assert::same(['third', 'second', 'first'], $texts);
});


test('toggle persists completion', function () {
	$facade = createFacade();
	$id = $facade->createTodo('Buy milk')->getId();

	Assert::true($facade->toggleTodo($id)?->isCompleted());
	Assert::true($facade->findAll()[0]->isCompleted());

	Assert::false($facade->toggleTodo($id)?->isCompleted());
	Assert::false($facade->findAll()[0]->isCompleted());
});


test('toggle of unknown id returns null', function () {
	Assert::null(createFacade()->toggleTodo(999));
});


test('delete removes only given todo', function () {
	$facade = createFacade();
	$keep = $facade->createTodo('keep');
	$drop = $facade->createTodo('drop');

	$facade->deleteTodo($drop->getId());

	$todos = $facade->findAll();
	Assert::count(1, $todos);
	Assert::same($keep->getId(), $todos[0]->getId());
});


test('delete of unknown id is no-op', function () {
	$facade = createFacade();
	$facade->createTodo('keep');

	$facade->deleteTodo(999);

	Assert::count(1, $facade->findAll());
});
