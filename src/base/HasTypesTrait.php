<?php
/**
 * Contains \deele\devkit\base\HasTypesTrait
 */

namespace deele\devkit\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * Class HasTypesTrait
 *
 * @property integer $type Type.
 *
 * @property-read array $types {@link HasTypesTrait::getTypes()}
 * @property-read string $typeTitle {@link HasTypesTrait::getTypeTitle()}
 *
 * Remember to add event listeners to your `ActiveRecord::init()`:
 * ~~~
 * public function init()
 * {
 *     $this->listenForTypeChanges();
 *     parent::init();
 * }
 * ~~~
 *
 * @author Nils (Deele) <deele@tuta.io>
 *
 * @package deele\devkit\base
 */
trait HasTypesTrait
{

    /**
     * Returns the name of event that is triggered after type change
     *
     * @return string
     */
    public static function getEventAfterTypeChangeName()
    {
        return 'afterTypeChange';
    }

    /**
     * Returns possible values of "type" attribute along with value titles
     *
     * @param string $language the language code (e.g. `en-US`, `en`).
     *
     * @return array
     */
    public static function getTypes($language = null)
    {
        return [];
    }

    /**
     * Creates type title based on identifier
     *
     * @param int $type Type identifier.
     * @param string $language the language code (e.g. `en-US`, `en`).
     *
     * @return null|string
     */
    public static function createTypeTitle($type, $language = null) {
        return ArrayHelper::getValue(static::getTypes($language), $type);
    }

    /**
     * Returns current "type" attribute value title
     *
     * @param string $language the language code (e.g. `en-US`, `en`).
     *
     * @return null|string
     */
    public function getTypeTitle($language = null)
    {
        return static::createTypeTitle($this->type, $language);
    }

    /**
     * @param int $newType
     * @param bool|true $autoSave
     * @param bool|false $runValidation
     *
     * @return bool
     */
    public function changeType($newType, $autoSave = true, $runValidation = false)
    {
        $success = true;
        if ($this->type != $newType && in_array($newType, $this->types)) {
            $this->type = $newType;
            if ($autoSave) {
                $success = $this->save($runValidation);
            }
        }

        return $success;
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function handleTriggersAfterTypeChange($event)
    {
        if (array_key_exists('type', $event->changedAttributes)) {
            $this->trigger(
                static::getEventAfterTypeChangeName(),
                new AfterTypeChangeEvent([
                    'oldType' => $event->changedAttributes['type'],
                    'newType' => $this->type,
                ])
            );
        }
    }

    /**
     * Attaches event listeners to object to listen for update and create events to handle type change
     */
    public function listenForTypeChanges()
    {
        if ($this instanceof ActiveRecord) {

            /**
             * @var TypesTrait|ActiveRecord $this
             */
            $this->on(
                $this::EVENT_AFTER_INSERT,
                [$this, 'handleTriggersAfterTypeChange']
            );
            $this->on(
                $this::EVENT_AFTER_UPDATE,
                [$this, 'handleTriggersAfterTypeChange']
            );
        }
    }
}
