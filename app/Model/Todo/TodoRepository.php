<?php

declare(strict_types=1);

namespace App\Model\Todo;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;


final class TodoRepository
{
	private const Table = 'todos';


	public function __construct(
		private Explorer $database,
	) {
	}


	public function setupTable(): void
	{
		$this->database->query('
			CREATE TABLE IF NOT EXISTS todos (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				text TEXT NOT NULL,
				completed BOOLEAN NOT NULL DEFAULT 0,
				created_at INTEGER NOT NULL
			)
		');
	}


	/** @return list<Todo> */
	public function findAll(): array
	{
		$rows = $this->database->table(self::Table)
			->order('created_at DESC, id DESC')
			->fetchAll();

		return array_values(array_map($this->createTodoFromRow(...), $rows));
	}


	public function findById(int $id): ?Todo
	{
		$row = $this->database->table(self::Table)->get($id);

		return $row ? $this->createTodoFromRow($row) : null;
	}


	public function save(Todo $todo): void
	{
		$this->database->table(self::Table)
			->where('id', $todo->getId())
			->update(['completed' => $todo->isCompleted()]);
	}


	public function insert(string $text): Todo
	{
		$row = $this->database->table(self::Table)->insert([
			'text' => $text,
			'completed' => false,
			'created_at' => time(),
		]);
		assert($row instanceof ActiveRow);

		return $this->createTodoFromRow($row);
	}


	public function delete(int $id): void
	{
		$this->database->table(self::Table)
			->where('id', $id)
			->delete();
	}


	private function createTodoFromRow(ActiveRow $row): Todo
	{
		return new Todo(
			(int) $row->id,
			(string) $row->text,
			(bool) $row->completed,
			(int) $row->created_at,
		);
	}
}
