<?php
/**
 * Contains \deele\devkit\db\SchemaHelper
 */

namespace deele\devkit\db;

use Yii;
use yii\db\Exception;
use yii\helpers\Inflector;

/**
 * Class SchemaHelper
 *
 * @author Nils (Deele) <deele@tuta.io>
 *
 * @package deele\devkit\db
 */
class SchemaHelper
{
    const FK_RESTRICT = 1;
    const FK_CASCADE = 2;
    const FK_SET_NULL = 3;
    const FK_NO_ACTION = 4;

    /**
     * Creates foreign key type name based on index
     *
     * @param int $type of the key
     *
     * @return string
     */
    public static function createForeignKeyType($type)
    {
        $map = [
            static::FK_RESTRICT  => 'RESTRICT',
            static::FK_CASCADE   => 'CASCADE',
            static::FK_SET_NULL  => 'SET NULL',
            static::FK_NO_ACTION => 'NO ACTION',
        ];
        return $map[$type];
    }

    /**
     * Creates foreign key name based on table name and column
     *
     * @param string $table the table
     * @param string $columns the column(s) that should be used to create the
     *   foreign key name.
     * @param null|string $name of the foreign key. Imploded column names is
     *   used when no name given.
     *
     * @return string
     */
    public static function createForeignKeyName($table, $columns, $name = null)
    {
        if (is_null($name)) {
            if (is_array($columns)) {
                $columnsStr = implode(
                    '_',
                    $columns
                );
            } else {
                $columnsStr = str_replace(
                    ',',
                    '_',
                    $columns
                );
            }
            $name = implode(
                '_',
                [
                    'fk',
                    Inflector::camelize($columnsStr),
                    Inflector::camelize(static::unPrefixedTable($table))
                ]
            );
        }
        if (strlen($name) > 64) {
            $name = substr(
                $name,
                0,
                64
            );
        }

        return $name;
    }

    /**
     * Creates index name based on table name and columns
     *
     * @param string|array $columns the column(s) that should be used to create
     *   the index name.
     * @param null|string $name of the index. Imploded column names is used when
     *   no name given.
     *
     * @return string
     */
    public static function createIndexName($columns, $name = null)
    {
        if (is_null($name)) {
            if (is_array($columns)) {
                foreach ($columns as &$column) {
                    $column = Inflector::camelize($column);
                }
                $columnsStr = implode(
                    '_',
                    $columns
                );
            } else {
                $columnsStr = str_replace(
                    ',',
                    '_',
                    Inflector::camelize($columns)
                );
            }
            $name = 'idx_' . $columnsStr;
        }
        if (strlen($name) > 64) {
            $name = substr(
                $name,
                0,
                64
            );
        }

        return $name;
    }

    /**
     * Creates table name surrounded by {{% and }} used for table name prefixing
     *
     * @param string $table
     *
     * @return string
     */
    public static function prefixedTable($table)
    {
        if (substr($table, 0, 3) != '{{%') {
            return '{{%' . $table . '}}';
        }

        return $table;
    }

    /**
     * Creates table name without surrounded {{% and }} used for table name prefixing
     *
     * @param string $table
     *
     * @return string
     */
    public static function unPrefixedTable($table)
    {
        if (substr($table, 0, 3) === '{{%') {
            return substr($table, 3, -2);
        }

        return $table;
    }

    /**
     * Returns true, if given tables does exist
     *
     * @param string|array $tableNames
     * @param bool $outputErrors
     * @param bool $prefixNames
     *
     * @return bool
     */
    public static function tablesExist($tableNames, $outputErrors = true, $prefixNames = true)
    {
        if (is_string($tableNames)) {
            $tableNames = explode(
                ',',
                $tableNames
            );
        }
        foreach ($tableNames as $tableName) {
            $tableSchema = Yii::$app->db->schema->getTableSchema(
                ($prefixNames ? static::prefixedTable($tableName) : $tableName)
            );
            if ($tableSchema === null) {
                return false;
            }
        }

        return true;
    }
}
