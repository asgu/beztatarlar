<?php


namespace Modules\Translation\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class UniqueIntlRule implements Rule
{
    protected ?string $_column;
    protected string $_table;
    protected string $_tableIntl;
    protected string $_idColumn = 'id';
    protected mixed $_ignore;
    protected Model $_model;
    protected array $addWhereClosure = [];
    protected array $addWhereIntlClosure = [];

    /**
     * UniqueIntlRule constructor.
     *
     * @param Model $model
     * @param $column
     * @param null $tableIntl
     */
    public function __construct(Model $model, $column = null, $tableIntl = null)
    {
        $this->_model = $model;
        $this->_column = $column;
        $this->_table = $model->getTable();
        $this->_tableIntl = $tableIntl ?? $this->generationTableIntl();
    }

    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $query = $this->buildQuery($value);
        $queryIntl = $this->buildQueryIntl($this->_model->$attribute);

        return !$query->exists() && !$queryIntl->exists();
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return trans('validation.unique');
    }

    /**
     * @param Closure $closure
     *
     * @return $this
     */
    public function addWhereClosure(Closure $closure): static
    {
        $this->addWhereClosure[] = $closure;
        return $this;
    }

    public function addWhereIntlClosure(Closure $closure): static
    {
        $this->addWhereIntlClosure[] = $closure;
        return $this;
    }

    /**
     * @param null $idColumn
     *
     * @return $this
     */
    public function ignorePrimaryModel($idColumn = null): static
    {
        $this->_idColumn = $idColumn ?? $this->_model->getKeyName();
        $this->_ignore = $this->_model->{$this->_idColumn};

        return $this;
    }

    /**
     * @return string
     */
    protected function generationTableIntl(): string
    {
        return $this->_table . '_intl';
    }

    /**
     * @param $value
     *
     * @return Builder
     */
    protected function buildQuery($value): Builder
    {
        $builder = DB::table($this->_table);
        $this->addWhereValue($builder, $value);

        foreach ($this->addWhereClosure as $valueWhere) {
            if (is_callable($valueWhere)) {
                $builder = $valueWhere($builder);
            }
        }

        if ($this->_ignore !== null) {
            $builder->where($this->_idColumn, '!=', $this->_ignore);
        }

        return $builder;
    }

    /**
     * @param $value
     *
     * @return Builder
     */
    protected function buildQueryIntl($value): Builder
    {
        $builder = DB::table($this->_tableIntl);
        $this->addWhereValue($builder, $value);

        foreach ($this->addWhereIntlClosure as $valueWhere) {
            if (is_callable($valueWhere)) {
                $builder = $valueWhere($builder);
            }
        }

        if ($this->_ignore !== null) {
            $builder->where('model_id', '!=', $this->_ignore);
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param $value
     *
     * @return Builder
     */
    protected function addWhereValue(Builder $builder, $value): Builder
    {
        if ($this->_column === null) {
            return $builder;
        }

        if (is_array($value)) {
            $builder->whereIn($this->_column, $value);
        } else {
            $builder->where($this->_column, $value);
        }

        return $builder;
    }
}
