<?php

use KoldyAdmin\Response\ButtonDeleteResponse;
use KoldyAdmin\Response\FormDataResponse;
use KoldyAdmin\Response\FormSubmitResponse;
use KoldyAdmin\Response\Json;
use KoldyAdmin\Response\RemoteActionButtonResponse;
use KoldyAdmin\Response\TableResponse;

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
    public static function isBootstrapColor(string $color)
    {
        return in_array($color, self::getBootstrapColors());
    }

    /**
     * @param string $color
     *
     * @return bool
     */
    public static function isBaseBootstrapColor(string $color)
    {
        return in_array($color, self::getBaseBootstrapColors());
    }

    /**
     * @return Json
     */
    public static function response(): Json
    {
        return new Json();
    }

    /**
     * @return TableResponse
     */
    public static function tableResponse(): TableResponse
    {
        return new TableResponse();
    }

    /**
     * @return ButtonDeleteResponse
     */
    public static function buttonDeleteResponse(): ButtonDeleteResponse
    {
        return new ButtonDeleteResponse();
    }

    /**
     * @return RemoteActionButtonResponse
     */
    public static function remoteActionButtonResponse(): RemoteActionButtonResponse
    {
        return new RemoteActionButtonResponse();
    }

    /**
     * @return FormDataResponse
     */
    public static function formDataResponse(): FormDataResponse
    {
        return new FormDataResponse();
    }

    /**
     * @return FormSubmitResponse
     */
    public static function formSubmitResponse(): FormSubmitResponse
    {
        return new FormSubmitResponse();
    }

}
