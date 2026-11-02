<?php

declare(strict_types=1);

use App\Core\RouterFactory;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/** @return array<string, mixed> */
function matchUrl(string $url): array
{
	$params = RouterFactory::createRouter()->match(new Request(new UrlScript($url, '/')));
	Assert::notNull($params);

	return $params ?? [];
}


test('root maps to todo list', function () {
	Assert::same(
		['presenter' => 'Todo', 'action' => 'default', 'id' => null],
		matchUrl('http://localhost/'),
	);
});


test('signal url carries id', function () {
	$params = matchUrl('http://localhost/todo/default/5?do=toggleTodo');

	Assert::same('Todo', $params['presenter']);
	Assert::same('5', $params['id']);
	Assert::same('toggleTodo', $params['do']);
});
