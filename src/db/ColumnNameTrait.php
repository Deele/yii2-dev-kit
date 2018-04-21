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
 * @package common\base
 */
trait ColumnNameTrait
{

    /**
     * @param string $column
     *
     * @return string
     */
    public function columnName($column)
    {
        return $column;
    }
}