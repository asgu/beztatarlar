<?php


namespace Modules\Teacher\Validation\Rules;


use Illuminate\Contracts\Validation\Rule;

class ColumnValueCountRule implements Rule
{

    private $value;
    private $count;
    private string $modelClass;

    public function __construct(string $modelClass, $value, $count)
    {
        $this->value = $value;
        $this->count = $count;
        $this->modelClass = $modelClass;
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        if ($value != $this->value) {
            return true;
        }

        $count = $this->modelClass::query()->where($attribute, $value)->count();
        return $count < $this->count;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return __('app.teacher.errors.maxCount');
    }
}
