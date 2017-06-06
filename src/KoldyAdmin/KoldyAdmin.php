<?php declare(strict_types=1);

namespace KoldyAdmin;

final class KoldyAdmin
{

    public const COLOR_SUCCESS = 'success';
    public const COLOR_DANGER = 'danger';
    public const COLOR_INFO = 'info';
    public const COLOR_WARNING = 'warning';
    public const COLOR_MUTED = 'muted';
    public const COLOR_PRIMARY = 'primary';
    public const COLOR_WHITE = 'white';

    /**
     * @return array
     */
    public static function getBaseBootstrapColors(): array
    {
        return [
          self::COLOR_SUCCESS,
          self::COLOR_DANGER,
          self::COLOR_INFO,
          self::COLOR_WARNING
        ];
    }

    /**
     * @return array
     */
    public static function getBootstrapColors(): array
    {
        return [
          self::COLOR_SUCCESS,
          self::COLOR_DANGER,
          self::COLOR_INFO,
          self::COLOR_WARNING,
          self::COLOR_MUTED,
          self::COLOR_PRIMARY,
          self::COLOR_WHITE
        ];
    }

    /**
     * @param string $color
     *
     * @return bool
     */
    public static function isBootstrapColor(string $color): bool
    {
        return in_array($color, self::getBootstrapColors());
    }

    /**
     * @param string $color
     *
     * @return bool
     */
    public static function isBaseBootstrapColor(string $color): bool
    {
        return in_array($color, self::getBaseBootstrapColors());
    }

}
