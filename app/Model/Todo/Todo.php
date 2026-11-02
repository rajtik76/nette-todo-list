<?php

declare(strict_types=1);

namespace App\Model\Todo;


final class Todo
{
	private int $createdAt;


	public function __construct(
		private int $id,
		private string $text,
		private bool $completed = false,
		?int $createdAt = null,
	) {
		$this->createdAt = $createdAt ?? time();
	}


	public function getId(): int
	{
		return $this->id;
	}


	public function getText(): string
	{
		return $this->text;
	}


	public function isCompleted(): bool
	{
		return $this->completed;
	}


	public function getCreatedAt(): int
	{
		return $this->createdAt;
	}


	public function toggle(): void
	{
		$this->completed = !$this->completed;
	}
}
