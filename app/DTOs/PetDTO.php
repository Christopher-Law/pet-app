<?php

namespace App\DTOs;

use App\Enums\Breed;
use App\Enums\Sex;
use App\Enums\Type;
use Carbon\Carbon;

class PetDTO
{
    public bool $explicitlySetDangerous = false;

    public function __construct(
        public readonly string $name,
        public readonly ?Type $type = null,
        public readonly ?Breed $breed = null,
        public readonly ?Carbon $dateOfBirth = null,
        public readonly ?Sex $sex = null,
        public readonly bool $isDangerousAnimal = false,
    ) {}

    public static function fromArray(array $data): self
    {
        $dto = new self(
            name: $data['name'],
            type: isset($data['type']) ? Type::tryFrom($data['type']) : null,
            breed: isset($data['breed']) ? Breed::tryFrom($data['breed']) : null,
            dateOfBirth: isset($data['date_of_birth']) ?
                (is_string($data['date_of_birth']) ? Carbon::parse($data['date_of_birth']) : $data['date_of_birth'])
                : null,
            sex: isset($data['sex']) ? Sex::tryFrom($data['sex']) : null,
            isDangerousAnimal: (bool) ($data['is_dangerous_animal'] ?? false),
        );

        // Track if dangerous animal flag was explicitly provided
        $dto->explicitlySetDangerous = array_key_exists('is_dangerous_animal', $data);

        return $dto;
    }

    public static function fromRequest(array $validatedData): self
    {
        return self::fromArray($validatedData);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type?->value,
            'breed' => $this->breed?->value,
            'date_of_birth' => $this->dateOfBirth,
            'sex' => $this->sex?->value,
            'is_dangerous_animal' => $this->isDangerousAnimal,
        ];
    }

    public function toModelArray(): array
    {
        return array_filter($this->toArray(), fn ($value) => $value !== null);
    }

    public function withDangerousAnimal(bool $isDangerous): self
    {
        $newDto = new self(
            name: $this->name,
            type: $this->type,
            breed: $this->breed,
            dateOfBirth: $this->dateOfBirth,
            sex: $this->sex,
            isDangerousAnimal: $isDangerous,
        );

        $newDto->explicitlySetDangerous = true; // Mark as explicitly set when modified

        return $newDto;
    }

    public function hasBreed(): bool
    {
        return $this->breed !== null;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function hasDateOfBirth(): bool
    {
        return $this->dateOfBirth !== null;
    }

    public function hasSex(): bool
    {
        return $this->sex !== null;
    }
}
