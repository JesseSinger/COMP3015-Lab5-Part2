<?php

class Photo
{
    protected ?int $id;
    private ?string $filename;
    private ?string $filepath;
    private ?string $metadata; // JSON string of metadata
    private ?string $uploadedAt;

    public function __construct(
        ?int $id = null,
        ?string $filename = null,
        ?string $filepath = null,
        ?string $metadata = null,
        ?string $uploadedAt = null
    ) {
        $this->setId($id);
        $this->setFilename($filename);
        $this->setFilepath($filepath);
        $this->setMetadata($metadata);
        $this->setUploadedAt($uploadedAt);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    public function setFilepath(?string $filepath): void
    {
        $this->filepath = $filepath;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(?string $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getUploadedAt(): ?string
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(?string $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }


    public function fill(array $attributes): self
    {
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }

        return $this;
    }

}
