<?php declare(strict_types=1);

namespace KoldyAdmin\Db\Traits;

use DateTime;
use DateTimeZone;

/**
 * Trait CreatedAtTrait
 * @package KoldyAdmin\Db\Traits
 * @property string created_at
 */
trait CreatedAtTrait
{

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAtDateTime(): DateTime
    {
        return new DateTime($this->getCreatedAt(), new DateTimeZone('UTC'));
    }

}
