<?php declare(strict_types=1);

namespace KoldyAdmin\Response;

class ButtonDeleteResponse extends Json
{

    /**
     * @return ButtonDeleteResponse
     */
    public function success(): self
    {
        $this->set('success', true);
        return $this;
    }
}
