<?php declare(strict_types=1);

namespace KoldyAdmin\Response\TableResponse;

use Closure;
use Koldy\Response\Json;

abstract class AbstractTableResponse extends Json
{

    protected const RESPONSE_KEY = 'table_data';

    /**
     * @var Closure|null
     */
    protected $resultSetModifier = null;

    /**
     * @var bool
     */
    protected $returnAll = false;

    /**
     * @param Closure $function
     *
     * @return AbstractTableResponse
     */
    public function setResultSetModifier(Closure $function): self
    {
        $this->resultSetModifier = $function;
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasResultSetModifier(): bool
    {
        return $this->resultSetModifier instanceof Closure;
    }

    /**
     * @return Closure
     */
    protected function getResultSetModifier(): Closure
    {
        return $this->resultSetModifier;
    }

    /**
     * @return AbstractTableResponse
     */
    public function returnAll(): self
    {
        $this->returnAll = true;
        return $this;
    }

}