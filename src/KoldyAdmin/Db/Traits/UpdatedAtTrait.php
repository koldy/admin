<?php declare(strict_types=1);

namespace KoldyAdmin\Db\Traits;

use DateTime;
use DateTimeZone;

/**
 * Trait
 * @property string updated_at
 */
trait UpdatedAtTrait
{

    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->updated_at !== null;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        if (!$this->isUpdated()) {
            return null;
        }

        return $this->updated_at;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAtDateTime(): ?DateTime
    {
        if (!$this->isUpdated()) {
            return null;
        }

        return new DateTime($this->updated_at, new DateTimeZone('UTC'));
    }

}
