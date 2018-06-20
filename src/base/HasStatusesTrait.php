<?php
/**
 * Contains \deele\devkit\base\HasStatusesTrait
 */

namespace deele\devkit\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * Class HasStatusesTrait
 *
 * @property integer $status Status.
 *
 * @property-read array $statuses {@link HasStatusesTrait::getStatuses()}
 * @property-read string $statusTitle {@link HasStatusesTrait::getStatusTitle()}
 *
 * Remember to add event listeners to your `ActiveRecord::init()`:
 * ~~~
 * public function init()
 * {
 *     $this->listenForStatusChanges();
 *     parent::init();
 * }
 * ~~~
 *
 * @author Nils (Deele) <deele@tuta.io>
 *
 * @package deele\devkit\base
 */
trait HasStatusesTrait
{

    /**
     * Returns the name of event that is triggered after status change
     *
     * @return string
     */
    public static function getEventAfterStatusChangeName()
    {
        return 'afterStatusChange';
    }

    /**
     * Returns possible values of "status" attribute along with value titles
     *
     * @param string $language the language code (e.g. `en-US`, `en`).
     *
     * @return array
     */
    public static function getStatuses($language = null)
    {
        return [];
    }

    /**
     * Creates status title based on identifier
     *
     * @param int $status Status identifier.
     * @param string $language the language code (e.g. `en-US`, `en`).
     *
     * @return null|string
     */
    public static function createStatusTitle($status, $language = null) {
        return ArrayHelper::getValue(static::getStatuses($language), $status);
    }

    /**
     * Returns current "status" attribute value title
     *
     * @param string $language the language code (e.g. `en-US`, `en`).
     *
     * @return null|string
     */
    public function getStatusTitle($language = null)
    {
        return static::createStatusTitle($this->status, $language);
    }

    /**
     * @param int $newStatus
     * @param bool|true $autoSave
     * @param bool|false $runValidation
     *
     * @return bool
     */
    public function changeStatus($newStatus, $autoSave = true, $runValidation = false)
    {
        $success = true;
        if ($this->status != $newStatus && in_array($newStatus, $this->statuses)) {
            $this->status = $newStatus;
            if ($autoSave) {
                $success = $this->save($runValidation);
            }
        }

        return $success;
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function handleTriggersAfterStatusChange($event)
    {
        if (array_key_exists('status', $event->changedAttributes)) {
            $this->trigger(
                static::getEventAfterStatusChangeName(),
                new AfterStatusChangeEvent([
                    'oldStatus' => $event->changedAttributes['status'],
                    'newStatus' => $this->status,
                ])
            );
        }
    }

    /**
     * Attaches event listeners to object to listen for update and create events to handle status change
     */
    public function listenForStatusChanges()
    {
        if ($this instanceof ActiveRecord) {

            /**
             * @var StatusesTrait|ActiveRecord $this
             */
            $this->on(
                $this::EVENT_AFTER_INSERT,
                [$this, 'handleTriggersAfterStatusChange']
            );
            $this->on(
                $this::EVENT_AFTER_UPDATE,
                [$this, 'handleTriggersAfterStatusChange']
            );
        }
    }
}
