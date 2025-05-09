<?php

declare(strict_types=1);

namespace App\Model\Todo;

/**
 * Facade class that provides a simplified interface for working with Todo items.
 * Acts as an intermediary between the presentation layer and the repository.
 */
final class TodoFacade
{
    public function __construct(
        private TodoRepository $todoRepository,
    ) {
        $this->todoRepository->setupTable();
    }

    /**
     * Retrieves all todo items.
     * @return array<Todo> Array of Todo objects
     */
    public function findAll(): array
    {
        return $this->todoRepository->findAll();
    }

    /**
     * Creates a new todo item.
     * @param string $text The text content of the todo item
     * @return Todo The created Todo object
     */
    public function createTodo(string $text): Todo
    {
        return $this->todoRepository->insert($text);
    }

    /**
     * Toggles the completion status of a todo item.
     * @param int $id The ID of the todo item to toggle
     * @return Todo|null The updated Todo object or null if not found
     */
    public function toggleTodo(int $id): ?Todo
    {
        $todo = $this->todoRepository->findById($id);
        if ($todo) {
            $todo->toggle();
            $this->todoRepository->save($todo);
        }

        return $todo;
    }

    /**
     * Deletes a todo item.
     * @param int $id The ID of the todo item to delete
     */
    public function deleteTodo(int $id): void
    {
        $this->todoRepository->delete($id);
    }
}
