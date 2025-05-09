<?php

declare(strict_types=1);

namespace App\Model\Todo;

/**
 * Entity class representing a Todo item.
 * Contains properties and methods for managing a single todo item.
 */
final class Todo
{
    public function __construct(
        private int $id,
        private string $text,
        private bool $completed = false,
        private ?int $createdAt = null,
    ) {
        $this->createdAt = $createdAt ?? date('U');
    }

    /**
     * Gets the ID of the todo item.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the text content of the todo item.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Checks if the todo item is completed.
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * Gets the creation timestamp of the todo item.
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * Toggles the completion status of the todo item.
     * If it was completed, it becomes incomplete, and vice versa.
     */
    public function toggle(): void
    {
        $this->completed = !$this->completed;
    }
}
