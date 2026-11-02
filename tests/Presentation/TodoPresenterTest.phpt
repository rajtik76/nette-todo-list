<?php declare(strict_types=1);

use App\Model\Todo\TodoFacade;
use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use Nette\DI\Container;
use Nette\Http;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


/**
 * @param array<string, string> $params
 * @param array<string, string> $post
 */
function runPresenter(
	Container $container,
	string $method = 'GET',
	array $params = [],
	array $post = [],
	bool $sameSite = true,
): Nette\Application\Response
{
	$container->removeService('http.request');
	$container->addService('http.request', new Http\Request(
		new Http\UrlScript('http://localhost/', '/'),
		post: $post,
		cookies: $sameSite ? [Http\Helpers::StrictCookieName => '1'] : [],
		method: $method,
	));

	$presenter = $container->getByType(IPresenterFactory::class)->createPresenter('Todo');
	assert($presenter instanceof Presenter);
	$presenter->autoCanonicalize = false;

	return $presenter->run(new Request('Todo', $method, ['action' => 'default'] + $params, $post));
}


function html(Nette\Application\Response $response): string
{
	Assert::type(TextResponse::class, $response);
	assert($response instanceof TextResponse);

	return (string) $response->getSource();
}


test('renders empty list', function () {
	Assert::contains('No todos yet', html(runPresenter(createContainer())));
});


test('renders todos escaped', function () {
	$container = createContainer();
	$container->getByType(TodoFacade::class)->createTodo('<script>alert(1)</script>');

	$html = html(runPresenter($container));

	Assert::notContains('<script>alert(1)</script>', $html);
	Assert::contains('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
});


test('form submit creates todo and redirects', function () {
	$container = createContainer();

	$response = runPresenter($container, 'POST', ['do' => 'todoForm-submit'], ['text' => 'Buy milk', 'add' => 'Add Todo']);

	Assert::type(RedirectResponse::class, $response);
	$todos = $container->getByType(TodoFacade::class)->findAll();
	Assert::count(1, $todos);
	Assert::same('Buy milk', $todos[0]->getText());
});


test('empty form submit is rejected', function () {
	$container = createContainer();

	$response = runPresenter($container, 'POST', ['do' => 'todoForm-submit'], ['text' => '', 'add' => 'Add Todo']);

	Assert::contains('Please enter a todo text', html($response));
	Assert::same([], $container->getByType(TodoFacade::class)->findAll());
});


test('toggle signal completes todo', function () {
	$container = createContainer();
	$id = $container->getByType(TodoFacade::class)->createTodo('Buy milk')->getId();

	$response = runPresenter($container, params: ['do' => 'toggleTodo', 'id' => (string) $id]);

	Assert::type(RedirectResponse::class, $response);
	Assert::true($container->getByType(TodoFacade::class)->findAll()[0]->isCompleted());
});


test('delete signal removes todo', function () {
	$container = createContainer();
	$id = $container->getByType(TodoFacade::class)->createTodo('Buy milk')->getId();

	runPresenter($container, params: ['do' => 'deleteTodo', 'id' => (string) $id]);

	Assert::same([], $container->getByType(TodoFacade::class)->findAll());
});


test('cross-site signal is ignored', function () {
	$container = createContainer();
	$id = $container->getByType(TodoFacade::class)->createTodo('Buy milk')->getId();

	$response = runPresenter($container, params: ['do' => 'deleteTodo', 'id' => (string) $id], sameSite: false);

	Assert::type(RedirectResponse::class, $response);
	Assert::count(1, $container->getByType(TodoFacade::class)->findAll());
});
