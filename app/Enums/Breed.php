<?php

namespace App\Enums;

enum Breed: string
{
    case PITBULL = 'Pitbull';
    case MASTIFF = 'Mastiff';
    case GERMAN_SHEPHERD = 'German Shepherd';
    case GOLDEN_RETRIEVER = 'Golden Retriever';
    case LABRADOR_RETRIEVER = 'Labrador Retriever';
    case BULLDOG = 'Bulldog';
    case ROTTWEILER = 'Rottweiler';
    case BEAGLE = 'Beagle';
    case POODLE = 'Poodle';
    case SIBERIAN_HUSKY = 'Siberian Husky';
    case PERSIAN = 'Persian';
    case MAINE_COON = 'Maine Coon';
    case BRITISH_SHORTHAIR = 'British Shorthair';
    case RAGDOLL = 'Ragdoll';
    case SIAMESE = 'Siamese';
    case AMERICAN_SHORTHAIR = 'American Shorthair';
    case ABYSSINIAN = 'Abyssinian';
    case RUSSIAN_BLUE = 'Russian Blue';
    case SCOTTISH_FOLD = 'Scottish Fold';
    case BENGAL = 'Bengal';
    case DONT_KNOW = 'dont_know';
    case MIXED = 'mixed';

    public function getLabel(): string
    {
        return match ($this) {
            self::DONT_KNOW => "I don't know",
            self::MIXED => 'Mixed breed',
            default => $this->value
        };
    }

    public static function getDogBreeds(): array
    {
        return [
            self::BEAGLE,
            self::BULLDOG,
            self::GERMAN_SHEPHERD,
            self::GOLDEN_RETRIEVER,
            self::LABRADOR_RETRIEVER,
            self::MASTIFF,
            self::PITBULL,
            self::POODLE,
            self::ROTTWEILER,
            self::SIBERIAN_HUSKY,
        ];
    }

    public static function getCatBreeds(): array
    {
        return [
            self::ABYSSINIAN,
            self::AMERICAN_SHORTHAIR,
            self::BENGAL,
            self::BRITISH_SHORTHAIR,
            self::MAINE_COON,
            self::PERSIAN,
            self::RAGDOLL,
            self::RUSSIAN_BLUE,
            self::SCOTTISH_FOLD,
            self::SIAMESE,
        ];
    }

    public static function getDangerousBreeds(): array
    {
        return [
            self::PITBULL,
            self::MASTIFF,
        ];
    }

    public function isDangerous(): bool
    {
        return in_array($this, self::getDangerousBreeds());
    }
}
