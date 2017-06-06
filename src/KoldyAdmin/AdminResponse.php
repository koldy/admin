<?php declare(strict_types=1);

namespace KoldyAdmin;

use KoldyAdmin\Response\ButtonDeleteResponse;
use KoldyAdmin\Response\FormDataResponse;
use KoldyAdmin\Response\FormSubmitResponse;
use KoldyAdmin\Response\Json;
use KoldyAdmin\Response\RemoteActionButtonResponse;
use KoldyAdmin\Response\TableResponse;

class AdminResponse
{

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
