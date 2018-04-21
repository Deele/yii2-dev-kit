<?php
/**
 * Contains \deele\devkit\db\ActiveQueryHelper
 */

namespace deele\devkit\db;

use yii\db\ActiveQuery;

/**
 * Class ActiveQueryHelper
 *
 * @author Nils (Deele) <deele@tuta.io>
 */
class ActiveQueryHelper
{

    /**
     * @param ActiveQuery $query
     * @param string $columnName
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string|null $conditionType
     *
     * @return ActiveQuery|array
     */
    public static function byBooleanValue(
        &$query,
        $columnName,
        $state = true,
        $conditionType = 'and'
    ) {
        if (is_bool($state)) {
            if ($state) {
                $condition = [
                    '=',
                    $columnName,
                    1
                ];
            } else {
                $condition = [
                    '=',
                    $columnName,
                    0
                ];
            }
        } else {
            $condition = [
                'IS',
                $columnName,
                $state
            ];
        }
        if ($conditionType == 'and') {
            return $query->andOnCondition($condition);
        } elseif ($conditionType == 'or') {
            return $query->orOnCondition($condition);
        } else {
            return $condition;
        }
    }

    /**
     * @param ActiveQuery $query
     * @param string $columnName
     * @param null|string|\DateTime $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string|null $conditionType
     *
     * @return ActiveQuery|array
     */
    public static function byDateTime(
        &$query,
        $columnName,
        $value,
        $state = true,
        $conditionType = 'and'
    ) {
        if (is_null($value)) {
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        'IS',
                        $columnName,
                        $value
                    ];
                } else {
                    $condition = [
                        'IS NOT',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        } else {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        $columnName => $value
                    ];
                } else {
                    $condition = [
                        '!=',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        }
        if ($conditionType == 'and') {
            return $query->andOnCondition($condition);
        } elseif ($conditionType == 'or') {
            return $query->orOnCondition($condition);
        } else {
            return $condition;
        }
    }

    /**
     * @param ActiveQuery $query
     * @param string $columnName
     * @param null|string|array $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param \Closure|bool $processValue
     * @param string|null $conditionType
     *
     * @return ActiveQuery|array
     */
    public static function byStringValue(
        &$query,
        $columnName,
        $value,
        $state = true,
        $processValue = false,
        $conditionType = 'and'
    ) {
        if (is_null($value)) {
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        'LIKE',
                        $columnName,
                        $value
                    ];
                } else {
                    $condition = [
                        'NOT LIKE',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        } elseif (is_array($value)) {
            if ($processValue instanceof \Closure) {
                foreach ($value as $k => $v) {
                    $value[$k] = call_user_func(
                        $processValue,
                        $v
                    );
                }
            }
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        'IN',
                        $columnName,
                        $value
                    ];
                } else {
                    $condition = [
                        'NOT IN',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        } else {
            if ($processValue instanceof \Closure) {
                $value = call_user_func(
                    $processValue,
                    $value
                );
            }
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        'LIKE',
                        $columnName,
                        $value
                    ];
                } else {
                    $condition = [
                        'NOT LIKE',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        }
        if ($conditionType == 'and') {
            return $query->andOnCondition($condition);
        } elseif ($conditionType == 'or') {
            return $query->orOnCondition($condition);
        } else {
            return $condition;
        }
    }

    /**
     * @param ActiveQuery $query
     * @param string $columnName
     * @param null|string|int|array $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param \Closure|bool $processValue
     * @param string|null $conditionType
     *
     * @return ActiveQuery|array
     */
    public static function byNumericValue(
        &$query,
        $columnName,
        $value,
        $state = true,
        $processValue = false,
        $conditionType = 'and'
    ) {
        if (is_null($value)) {
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        'IS',
                        $columnName,
                        $value
                    ];
                } else {
                    $condition = [
                        'IS NOT',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        } elseif (is_array($value)) {
            if ($processValue instanceof \Closure) {
                foreach ($value as $k => $v) {
                    $value[$k] = call_user_func(
                        $processValue,
                        $v
                    );
                }
            }
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        'IN',
                        $columnName,
                        $value
                    ];
                } else {
                    $condition = [
                        'NOT IN',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        } else {
            if ($processValue instanceof \Closure) {
                $value = call_user_func(
                    $processValue,
                    $value
                );
            }
            if (is_bool($state)) {
                if ($state) {
                    $condition = [
                        $columnName => $value
                    ];
                } else {
                    $condition = [
                        '!=',
                        $columnName,
                        $value
                    ];
                }
            } else {
                $condition = [
                    $state,
                    $columnName,
                    $value
                ];
            }
        }
        if ($conditionType == 'and') {
            return $query->andOnCondition($condition);
        } elseif ($conditionType == 'or') {
            return $query->orOnCondition($condition);
        } else {
            return $condition;
        }
    }
}