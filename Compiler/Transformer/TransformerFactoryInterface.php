<?php

namespace Jane\AutoMapper\Compiler\Transformer;

use Jane\AutoMapper\MapperConfigurationInterface;
use Symfony\Component\PropertyInfo\Type;

interface TransformerFactoryInterface
{
    /**
     * @param Type[] $sourcesTypes
     * @param Type[] $targetTypes
     */
    public function getTransformer(?array $sourcesTypes, ?array $targetTypes, MapperConfigurationInterface $mapperConfiguration): ?TransformerInterface;
}
