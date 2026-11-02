<?php declare(strict_types=1);

namespace App\Model\Todo;


final class TodoFacade
{
	public function __construct(
		private TodoRepository $todoRepository,
	) {
		$this->todoRepository->setupTable();
	}


	/** @return list<Todo> */
	public function findAll(): array
	{
		return $this->todoRepository->findAll();
	}


	public function createTodo(string $text): Todo
	{
		return $this->todoRepository->insert($text);
	}


	public function toggleTodo(int $id): ?Todo
	{
		$todo = $this->todoRepository->findById($id);
		if ($todo) {
			$todo->toggle();
			$this->todoRepository->save($todo);
		}

		return $todo;
	}


	public function deleteTodo(int $id): void
	{
		$this->todoRepository->delete($id);
	}
}
