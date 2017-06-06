<?php declare(strict_types=1);

namespace KoldyAdmin\Response\TableResponse;

use Koldy\Db\Adapter\PostgreSQL;
use Koldy\Db\Model;
use Koldy\Db\Where;
use Koldy\Validator;

class SingleTableModel extends AbstractTableResponse
{

    /**
     * @var null|mixed
     */
    private $where = null;

    /**
     * @var null|array
     */
    private $searchableFields = null;

    /**
     * @var null|string
     */
    private $modelClass = null;

    /**
     * @param string $modelClass
     *
     * @return SingleTableModel
     */
    public function modelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    /**
     * @param mixed $where
     *
     * @return SingleTableModel
     */
    public function where($where): self
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @param array $searchableFields
     *
     * @return SingleTableModel
     */
    public function searchableFields(array $searchableFields): self
    {
        $this->searchableFields = $searchableFields;
        return $this;
    }

    protected function prepareFlush(): void
    {
        parent::prepareFlush();

        $data = Validator::create([
          'page' => 'integer|min:1',
          'limit' => 'integer|min:1|max:10000',
          'sort' => 'minLength:1|maxLength:512',
          'direction' => 'anyOf:asc,desc',
          'query' => 'maxLength:255',
          'searchable_fields' => 'maxLength:512'
        ])->getDataObj();

        /** @var Model $className */
        $className = $this->modelClass;
        $userWhere = $this->where;
        $searchableFields = $this->searchableFields;
        $returnAll = $this->returnAll;

        if ($returnAll) {
            $limit = 10000;
            $page = 1;
            $start = 0;
            $orderField = null;
            $orderDirection = null;
            $query = null;
        } else {
            $limit = (int)$data->limit;
            $page = (int)$data->page;
            $start = ($page - 1) * $limit;
            $orderField = $data->sort ?? null;
            $orderDirection = $data->direction ?? null;
            $query = $data->query ?? null;
        }

        /** @var Where $where */
        $where = Where::init();

        if ($userWhere !== null) {
            if (is_array($userWhere)) {
                foreach ($userWhere as $field => $value) {
                    $where->where($field, $value);
                }
            } else if (is_numeric($userWhere) || $userWhere instanceof Where) {
                $where->where($userWhere);
            }
        }

        if ($searchableFields !== null) {
            $searchableFields = $this->use['searchable_fields'];
        } else if ($data->searchable_fields !== null) {
            $searchableFields = explode(',', $data->searchable_fields);
        }

        if ($query !== null && strlen(trim($query)) > 0 && is_array($searchableFields) && count($searchableFields) > 0) {
            $query = trim($query);
            $operator = $className::getAdapter() instanceof PostgreSQL ? 'ILIKE' : 'LIKE';

            $whereSearch = Where::init();
            foreach ($searchableFields as $searchableField) {
                $whereSearch->orWhere($searchableField, $operator, "%{$query}%");
            }

            $where->where($whereSearch);
        }

        if ($where->isEmpty()) {
            $where = null;
        }

        $count = $className::count($where);

        if ($count > 0) {
            $list = $className::fetch($where, null, $orderField, $orderDirection, $limit, $start);
        } else {
            // don't query again if count said there's nothing in table
            $list = [];
        }

        if ($this->hasResultSetModifier()) {
            $modifierFn = $this->getResultSetModifier();
            $list = $modifierFn($list);
        }

        $this->set(parent::RESPONSE_KEY, [
          'list' => $list,
          'count' => $count,
          'page' => $page,
          'limit' => $limit,
          'query' => $query
        ]);
    }

}
