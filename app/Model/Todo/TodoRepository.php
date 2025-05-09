<?php

declare(strict_types=1);

namespace App\Model\Todo;

use Nette;
use Nette\Database\Explorer;

/**
 * Repository class for managing Todo items in the database.
 * Handles CRUD operations for Todo entities.
 */
final class TodoRepository
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    /**
     * Creates the todos table in the database if it doesn't exist.
     */
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

    /**
     * Retrieves all todo items from the database, ordered by creation date (newest first).
     */
    public function findAll(): array
    {
        $rows = $this->database->table('todos')
            ->order('created_at DESC')
            ->fetchAll();

        return array_map(fn($row) => $this->createTodoFromRow($row), $rows);
    }

    /**
     * Finds a todo item by its ID.
     */
    public function findById(int $id): ?Todo
    {
        $row = $this->database->table('todos')
            ->where('id', $id)
            ->fetch();

        return $row ? $this->createTodoFromRow($row) : null;
    }

    /**
     * Updates an existing todo item in the database.
     * Currently only updates the 'completed' status.
     */
    public function save(Todo $todo): void
    {
        $this->database->table('todos')->where('id', $todo->getId())->update([
            'completed' => $todo->isCompleted(),
        ]);
    }

    /**
     * Creates a new todo item in the database.
     */
    public function insert(string $text): Todo
    {
        $row = $this->database->table('todos')->insert([
            'text' => $text,
            'completed' => false,
            'created_at' => date('U'),
        ]);

        return $this->createTodoFromRow($row);
    }

    /**
     * Deletes a todo item from the database by its ID.
     */
    public function delete(int $id): void
    {
        $this->database->table('todos')->where('id', $id)->delete();
    }

    /**
     * Creates a Todo object from a database row.
     */
    private function createTodoFromRow(Nette\Database\Table\ActiveRow $row): Todo
    {
        return new Todo(
            $row->id,
            $row->text,
            (bool) $row->completed,
            $row->created_at,
        );
    }
}
