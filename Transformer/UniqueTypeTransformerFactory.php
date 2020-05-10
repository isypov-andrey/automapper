<?php

namespace Jane\AutoMapper\Transformer;

use Jane\AutoMapper\MapperMetadataInterface;

/**
 * Reduce array of type to only one type on source and target.
 *
 * @author Joel Wurtz <jwurtz@jolicode.com>
 */
final class UniqueTypeTransformerFactory implements TransformerFactoryInterface
{
    private $chainTransformerFactory;

    public function __construct(ChainTransformerFactory $chainTransformerFactory)
    {
        $this->chainTransformerFactory = $chainTransformerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransformer(?array $sourcesTypes, ?array $targetTypes, MapperMetadataInterface $mapperMetadata): ?TransformerInterface
    {
        $nbSourcesTypes = $sourcesTypes ? \count($sourcesTypes) : 0;
        $nbTargetsTypes = $targetTypes ? \count($targetTypes) : 0;

        if (null === $sourcesTypes || 0 === $nbSourcesTypes || $nbSourcesTypes > 1) {
            return null;
        }

        if (null === $targetTypes || $nbTargetsTypes <= 1) {
            return null;
        }

        foreach ($targetTypes as $targetType) {
            if (null === $targetType) {
                continue;
            }

            $transformer = $this->chainTransformerFactory->getTransformer($sourcesTypes, [$targetType], $mapperMetadata);

            if (null !== $transformer) {
                return $transformer;
            }
        }

        return null;
    }
}
