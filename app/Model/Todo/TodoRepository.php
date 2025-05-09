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
     * @return array<Todo> Array of Todo objects
     */
    public function findAll(): array
    {
        $rows = $this->database->table('todos')
            ->order('created_at DESC')
            ->fetchAll();

        return array_map(fn(Nette\Database\Table\ActiveRow $row): Todo => $this->createTodoFromRow($row), $rows);
    }

    /**
     * Finds a todo item by its ID.
     * @param int $id The ID of the todo item to find
     * @return Todo|null The found Todo object or null if not found
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
     * @param Todo $todo The todo item to update
     */
    public function save(Todo $todo): void
    {
        $data = [
            'completed' => $todo->isCompleted(),
        ];

        $this->database->table('todos')
            ->where('id', $todo->getId())
            ->update($data);
    }

    /**
     * Creates a new todo item in the database.
     * @param string $text The text content of the todo item
     * @return Todo The created Todo object
     */
    public function insert(string $text): Todo
    {
        $data = [
            'text' => $text,
            'completed' => false,
            'created_at' => (int) date('U'),
        ];

        $row = $this->database->table('todos')->insert($data);

        return $this->createTodoFromRow($row);
    }

    /**
     * Deletes a todo item from the database by its ID.
     * @param int $id The ID of the todo item to delete
     */
    public function delete(int $id): void
    {
        $this->database->table('todos')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Creates a Todo object from a database row.
     * @param Nette\Database\Table\ActiveRow $row Database row with todo data
     * @return Todo Created Todo object
     */
    private function createTodoFromRow(Nette\Database\Table\ActiveRow $row): Todo
    {
        return new Todo(
            (int) $row->id,
            (string) $row->text,
            (bool) $row->completed,
            (int) $row->created_at,
        );
    }
}
