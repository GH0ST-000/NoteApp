<?php

namespace App\DTOs;

class GroupDTO
{
    public string $name;

    public bool $isPublished;

    public ?string $slug;

    /**
     * Create a new DTO instance.
     */
    public function __construct(
        string $name,
        bool $isPublished = false,
        ?string $slug = null
    ) {
        $this->name = $name;
        $this->isPublished = $isPublished;
        $this->slug = $slug;
    }

    /**
     * Create a DTO from request data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['is_published'] ?? false,
            $data['slug'] ?? null
        );
    }

    /**
     * Convert DTO to array for database operations.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'is_published' => $this->isPublished,
            'slug' => $this->slug,
        ];
    }
}
