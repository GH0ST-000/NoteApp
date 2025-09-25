<?php

namespace App\DTOs;

class NoteDTO
{
    public string $title;

    public string $content;

    public ?int $groupId;

    public bool $isPinned;

    public bool $isPublished;

    public ?string $slug;

    public ?string $imagePath;

    /**
     * @var \Illuminate\Http\UploadedFile|null
     */
    public $imageFile;

    /**
     * Create a new DTO instance.
     *
     * @param  \Illuminate\Http\UploadedFile|null  $imageFile
     */
    public function __construct(
        string $title,
        string $content,
        ?int $groupId = null,
        bool $isPinned = false,
        bool $isPublished = false,
        ?string $slug = null,
        ?string $imagePath = null,
        $imageFile = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->groupId = $groupId;
        $this->isPinned = $isPinned;
        $this->isPublished = $isPublished;
        $this->slug = $slug;
        $this->imagePath = $imagePath;
        $this->imageFile = $imageFile;
    }

    /**
     * Create a DTO from request data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['content'],
            $data['group_id'] ?? null,
            $data['is_pinned'] ?? false,
            $data['is_published'] ?? false,
            $data['slug'] ?? null,
            $data['image_path'] ?? null,
            $data['image'] ?? null
        );
    }

    /**
     * Convert DTO to array for database operations.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'group_id' => $this->groupId,
            'is_pinned' => $this->isPinned,
            'is_published' => $this->isPublished,
            'slug' => $this->slug,
            'image_path' => $this->imagePath,
        ];
    }
}
