<?php

/**
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Netresearch\HideUnavailableVariantsBundle\Decorator;

use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Oro\Bundle\ProductBundle\ProductVariant\TypeHandler\EnumTypeHandler;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * Decorator class, which removes nonexistent variants from the selection box
 * of configurable products.
 */
class EnumTypeHandlerDecorator extends EnumTypeHandler
{
    public function __construct(
        FormFactory $formFactory,
        string $productClass,
        ConfigManager $configManager,
        private readonly EnumTypeHandler $decorated
    ) {
        parent::__construct($formFactory, $productClass, $configManager);
    }

    /**
     * @param string $fieldName
     * @param array<string, bool> $availability
     * @param array<string, mixed> $options
     */
    public function createForm($fieldName, array $availability, array $options = []): FormInterface
    {
        // The class to be decorated creates the 'disable_values' option for a list
        // of all unavailable variants.
        // Since we want to completely remove these unavailable variants from the selection,
        // we set the option 'excluded_values' for this list.
        $notAvailableVariants = $this->getNotAvailableVariants($availability);
        if ($notAvailableVariants !== []) {
            $options['excluded_values'] = array_merge(
                $notAvailableVariants,
                $options['excluded_values'] ?? []
            );
        }

        return $this->decorated->createForm($fieldName, $availability, $options);
    }

    /**
     * Returns a list of options for which no variant products exist.
     *
     * Example: You have a color attribute (with the options "red", "white" and "black")
     * and a color-configurable product "T-shirt". If only one variant product
     * with the color 'white' exists, this function returns the array ['red', 'black'].
     *
     * @param array<string, bool> $availability
     *
     * @return string[]
     */
    private function getNotAvailableVariants(array $availability): array
    {
        $notAvailableVariants = array_filter(
            $availability,
            fn($item): bool => $item === false
        );

        return array_map('\strval', array_keys($notAvailableVariants));
    }
}
