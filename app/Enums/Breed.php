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
            self::PITBULL,
            self::MASTIFF,
            self::GERMAN_SHEPHERD,
            self::GOLDEN_RETRIEVER,
            self::LABRADOR_RETRIEVER,
            self::BULLDOG,
            self::ROTTWEILER,
            self::BEAGLE,
            self::POODLE,
            self::SIBERIAN_HUSKY,
        ];
    }

    public static function getCatBreeds(): array
    {
        return [
            self::PERSIAN,
            self::MAINE_COON,
            self::BRITISH_SHORTHAIR,
            self::RAGDOLL,
            self::SIAMESE,
            self::AMERICAN_SHORTHAIR,
            self::ABYSSINIAN,
            self::RUSSIAN_BLUE,
            self::SCOTTISH_FOLD,
            self::BENGAL,
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
