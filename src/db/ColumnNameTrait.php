<?php
/**
 * Contains \deele\devkit\db\ColumnNameTrait
 */

namespace deele\devkit\db;

/**
 * Trait ColumnName
 *
 * @author Nils (Deele) <deele@tuta.io>
 *
 * @package deele\devkit\db
 */
trait ColumnNameTrait
{

    /**
     * @param string $column
     *
     * @return string
     */
    public function columnName(string $column): string
    {
        return $column;
    }
}
