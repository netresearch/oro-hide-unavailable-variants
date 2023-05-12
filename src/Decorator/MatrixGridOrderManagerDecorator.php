<?php

/**
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Netresearch\HideUnavailableVariantsBundle\Decorator;

use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Provider\ProductVariantAvailabilityProvider;
use Oro\Bundle\ShoppingListBundle\Entity\ShoppingList;
use Oro\Bundle\ShoppingListBundle\Manager\EmptyMatrixGridInterface;
use Oro\Bundle\ShoppingListBundle\Manager\MatrixGridOrderManager;
use Oro\Bundle\ShoppingListBundle\Model\MatrixCollection;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Decorator, that removes rows and columns (for which no product variants exist)
 * from the attribute matrix view (Inline Matrix Form) of configurable products.
 */
class MatrixGridOrderManagerDecorator extends MatrixGridOrderManager
{
    public function __construct(
        PropertyAccessor $propertyAccessor,
        ProductVariantAvailabilityProvider $variantAvailability,
        EmptyMatrixGridInterface $emptyMatrixGridManager,
        private readonly MatrixGridOrderManager $decorated
    ) {
        parent::__construct($propertyAccessor, $variantAvailability, $emptyMatrixGridManager);
    }

    /**
     * Gets the matrix from the instance to be decorated and remove the rows and columns
     * for which there are no variant products
     */
    public function getMatrixCollection(Product $product, ShoppingList $shoppingList = null): MatrixCollection
    {
        $matrixCollection = $this->decorated->getMatrixCollection($product, $shoppingList);

        $newRows = [];
        $columnsExist = [];

        foreach ($matrixCollection->rows as $rowIndex => $row) {
            foreach ($row->columns as $columnIndex => $column) {
                if ($column->product !== null) {
                    $newRows[$rowIndex] = $row;
                    $columnsExist[$columnIndex] = true;
                }
            }
        }

        $newRows = array_values($newRows);

        foreach ($newRows as $row) {
            $newColumns = [];
            foreach ($row->columns as $columnIndex => $column) {
                if (isset($columnsExist[$columnIndex])) {
                    $newColumns[] = $column;
                }
            }
            $row->columns = $newColumns;
        }

        $matrixCollection->rows = $newRows;
        return $matrixCollection;
    }
}
