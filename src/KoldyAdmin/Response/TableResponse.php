<?php declare(strict_types=1);

namespace KoldyAdmin\Response;

use Koldy\Db\Query\ResultSet;
use KoldyAdmin\Response\TableResponse\ResultSetResponse;
use KoldyAdmin\Response\TableResponse\SingleTableModel;

class TableResponse extends Json
{


    /**
     * @param string $modelClass
     *
     * @return SingleTableModel
     */
    public function useModel(string $modelClass): SingleTableModel
    {
        return (new SingleTableModel())->modelClass($modelClass);
    }

    /**
     * @param ResultSet $resultSet
     *
     * @return ResultSetResponse
     */
    public function useResultSet(ResultSet $resultSet): ResultSetResponse
    {
        return (new ResultSetResponse())->resultSet($resultSet);
    }

}
