<?php declare(strict_types=1);

namespace KoldyAdmin\Response\TableResponse;

use Closure;
use Koldy\Db\Query\ResultSet;
use Koldy\Validator;

class ResultSetResponse extends AbstractTableResponse
{

    /**
     * @var null|ResultSet
     */
    private $resultSet = null;

    /**
     * @param ResultSet $resultSet
     *
     * @return ResultSetResponse
     */
    public function resultSet(ResultSet $resultSet): self
    {
        $this->resultSet = $resultSet;
        return $this;
    }

    protected function prepareFlush(): void
    {
        parent::prepareFlush();

        /** @var ResultSet $resultSet */
        $resultSet = $this->resultSet;
        $returnAll = $this->returnAll;
        $resultsModifier = $this->resultSetModifier;

        $data = Validator::create([
          'page' => 'integer|min:1',
          'limit' => 'integer|min:1|max:10000',
          'sort' => 'minLength:1|maxLength:512',
          'direction' => 'anyOf:asc,desc',
          'query' => 'maxLength:255',
          'searchable_fields' => 'maxLength:512'
        ])->getDataObj();

        if ($returnAll) {
            $page = 1;
            $limit = 10000;
            $sort = null;
            $direction = null;
            $query = null;
            $searchableFields = null;
        } else {
            $page = (int)$data->page;
            $limit = (int)$data->limit;
            $sort = $data->sort ?? null;
            $direction = $data->direction ?? null;
            $query = $data->query ?? null;
            $searchableFields = $data->searchable_fields ?? null;
        }

        // set search fields
        if (!$resultSet->hasSearchField() && $searchableFields !== null) {
            $resultSet->setSearchFields(explode(',', $searchableFields));
        }

        $resultSet->page($page, $limit);

        if ($sort !== null && $direction !== null) {
            $resultSet->orderBy($sort, $direction);
        }

        if ($query !== null) {
            $resultSet->search($query);
        }

        $count = $resultSet->count();
        if ($count > 0) {
            $list = $resultSet->fetchAll();
        } else {
            // don't query again if count said there's nothing in table
            $list = [];
        }

        if ($resultsModifier instanceof Closure) {
            $list = $resultsModifier($list);
        }

        $this->set(self::RESPONSE_KEY, [
          'list' => $list,
          'count' => $count,
          'page' => $page,
          'limit' => $limit,
          'query' => $query
        ]);
    }

}