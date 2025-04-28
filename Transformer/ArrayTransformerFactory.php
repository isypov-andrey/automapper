<?php

namespace Jane\AutoMapper\Transformer;

use Jane\AutoMapper\MapperMetadataInterface;
use Symfony\Component\PropertyInfo\Type;

/**
 * Create a decorated transformer to handle array type.
 *
 * @author Joel Wurtz <jwurtz@jolicode.com>
 */
final class ArrayTransformerFactory extends AbstractUniqueTypeTransformerFactory
{
    private $chainTransformerFactory;

    public function __construct(ChainTransformerFactory $chainTransformerFactory)
    {
        $this->chainTransformerFactory = $chainTransformerFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function createTransformer(Type $sourceType, Type $targetType, MapperMetadataInterface $mapperMetadata): ?TransformerInterface
    {
        if (!$sourceType->isCollection()) {
            return null;
        }

        if (!$targetType->isCollection()) {
            return null;
        }

        if (null === ($sourceType->getCollectionValueTypes()[0] ?? null) || null === ($targetType->getCollectionValueTypes()[0] ?? null)) {
            return new CopyTransformer();
        }

        $subItemTransformer = $this->chainTransformerFactory->getTransformer([$sourceType->getCollectionValueTypes()[0] ?? null], [$targetType->getCollectionValueTypes()[0] ?? null], $mapperMetadata);

        if (null !== $subItemTransformer) {
            return new ArrayTransformer($subItemTransformer);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority(): int
    {
        return 4;
    }
}
