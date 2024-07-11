<?php

/**
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Netresearch\HideUnavailableVariantsBundle\Decorator;

use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Provider\ProductVariantAvailabilityProvider;
use Oro\Bundle\ShoppingListBundle\Entity\ShoppingList;
use Oro\Bundle\ShoppingListBundle\Manager\EmptyMatrixGridInterface;
use Oro\Bundle\ShoppingListBundle\Manager\MatrixGridOrderManager;
use Oro\Bundle\ShoppingListBundle\Model\MatrixCollection;
use Oro\Bundle\ShoppingListBundle\Model\MatrixCollectionColumn;
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
        ManagerRegistry $doctrine,
        private readonly MatrixGridOrderManager $decorated
    ) {
        parent::__construct($propertyAccessor, $variantAvailability, $emptyMatrixGridManager, $doctrine);
    }

    /**
     * Gets the matrix from the instance to be decorated and remove the rows and columns
     * for which there are no variant products
     */
    public function getMatrixCollection(Product $product, ?\Oro\Bundle\ShoppingListBundle\Entity\ShoppingList $shoppingList = null): MatrixCollection
    {
        $matrixCollection = $this->decorated->getMatrixCollection($product, $shoppingList);

        $availableCols = [];
        foreach ($matrixCollection->rows as $rowIndex => $row) {
            if ($row->columns === []) {
                // remove rows without a variant product
                unset($matrixCollection->rows[$rowIndex]);
            } else {
                // memorize the columns that have variant products
                foreach ($row->columns as $colIndex => $col) {
                    if ($col instanceof MatrixCollectionColumn) {
                        $availableCols[$colIndex] = $matrixCollection->columns[$colIndex];
                    }
                }
            }
        }

        // rearrange columnIndex
        $matrixCollection->columns = array_values($availableCols);

        // rearrange columnIndex in rows
        $oldColumnIndexToNewColumnIndexMapping = array_flip(array_keys($availableCols));
        foreach ($matrixCollection->rows as $row) {
            $newCols = [];
            foreach ($row->columns as $oldColIndex => $col) {
                $newColIndex = $oldColumnIndexToNewColumnIndexMapping[$oldColIndex];
                $newCols[$newColIndex] = $col;
            }
            $row->columns = $newCols;
        }

        return $matrixCollection;
    }
}
