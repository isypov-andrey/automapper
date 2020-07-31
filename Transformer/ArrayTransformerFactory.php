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

        if (null === $sourceType->getCollectionValueType() || null === $targetType->getCollectionValueType()) {
            return new CopyTransformer();
        }

        $subItemTransformer = $this->chainTransformerFactory->getTransformer([$sourceType->getCollectionValueType()], [$targetType->getCollectionValueType()], $mapperMetadata);

        if (null !== $subItemTransformer) {
            return new ArrayTransformer($subItemTransformer);
        }

        return null;
    }
}
