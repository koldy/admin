<?php declare(strict_types=1);

namespace KoldyAdmin\Response;

class RemoteActionButtonResponse extends Json
{

    /**
     * @return RemoteActionButtonResponse
     */
    public function success(): self
    {
        $this->set('success', true);
        return $this;
    }
}
