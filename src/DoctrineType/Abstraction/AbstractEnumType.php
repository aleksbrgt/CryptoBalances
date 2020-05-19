<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\DoctrineType\Abstraction;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use RuntimeException;
use UnexpectedValueException;

abstract class AbstractEnumType extends Type
{
    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'SMALLINT';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        $dbValue = array_search($value, $this->getKeyValues());

        if (false === $dbValue) {
            throw new UnexpectedValueException(sprintf(
                'Cannot find key for value "%s"',
                $value
            ));
        }

        return (int) $dbValue;
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        $phpValue = $this->getKeyValues()[$value];

        if (null === $phpValue) {
            throw new RuntimeException(sprintf(
                'Cannot find value for the db value "%"',
                $value
            ));
        }

        return (string) $phpValue;
    }

    /**
     * @inheritDoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return array
     */
    abstract public function getKeyValues(): array;
}
