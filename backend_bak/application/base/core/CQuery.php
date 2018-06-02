<?php
namespace app\base\core;

use think\db\Query;

class CQuery extends Query
{

    public function like($field, $value = null, $options = []){
        return $this->compare($field, "LIKE", $value, $options);
    }

    public function lLike($field, $value = null, $options = []){
        return $this->compare($field, "LIKE", $value, array_merge($options, ['rLike' => false]));
    }

    public function rLike($field, $value = null, $options = []){
        return $this->compare($field, "LIKE", $value, array_merge($options, ['lLike' => false]));
    }

    public function notLike($field, $value = null, $options = []){
        return $this->compare($field, "NOT LIKE", $value, $options);
    }

    public function lNotLike($field, $value = null, $options = []){
        return $this->compare($field, "NOT LIKE", $value, array_merge($options, ['rLike' => false]));
    }

    public function rNotLike($field, $value = null, $options = []){
        return $this->compare($field, "NOT LIKE", $value, array_merge($options, ['lLike' => false]));
    }

    public function in($field, $value = null, $options = []){
        return $this->compare($field, "IN", $value, $options);
    }

    public function notIn($field, $value = null, $options = []){
        return $this->compare($field, "NOT IN", $value, $options);
    }

    public function filter($field, $op, $value = null, $options = []){
        return $this->compare($field, $op, $value, array_merge($options, ['allowEmpey' => false]));
    }

    public function compareOr($field, $op, $value = null, $options = []){
        return $this->compare($field, $op, $value, array_merge($options, ['logic' => 'OR']));
    }

    public function compare($field, $op, $value = null, $options = []){
        $options = array_merge([
            'logic' => 'AND', 
            'escape' => true, 
            'allowEmpey' => true,
            'lLike' => true,
            'rLike' => true,
        ], $options);

        $op = strtoupper(trim($op));


        if($value === null){
            $value = $op;
            $op = "eq";
        }

        if($options['allowEmpey'] && $value === ''){
            return $this;
        }

        if($op == "LIKE" || $op === "NOT LIKE"){
            if($options['escape']){
                $value=strtr($value,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\'));
            }
            if($options['lLike']){
                $value='%'.$value;
            }
            if($options['rLike']){
                $value=$value.'%';
            }
        }


        if(strtoupper($options['logic']) == "OR"){
            return $this->whereOr($field, $op, $value);
        }else{
            return $this->where($field, $op, $value);
        }
    }

    protected function parseWhereExp($logic, $field, $op, $condition, array $param = [], $strict = false)
    {
        if ($field instanceof $this) {
            foreach ((array)$field->getOptions('where') as $key => $options) {
                foreach ($options as $option) {
                    $this->options['where'][$key][] = $option;
                }
            }
            $this->bind = array_merge($this->bind, $field->bind);
            $this->options['order'] = array_merge((array)$this->getOptions('order'), (array)$field->getOptions('order'));
            return $this;
        }

        $logic = strtoupper($logic);

        if (is_string($field) && !empty($this->options['via']) && !strpos($field, '.')) {
            $field = $this->options['via'] . '.' . $field;
        }

        if ($field instanceof Expression) {
            return $this->whereRaw($field, is_array($op) ? $op : []);
        } elseif ($strict) {
            // 使用严格模式查询
            $where = [$field, $op, $condition];
        } elseif (is_array($field)) {
            // 解析数组批量查询
            return $this->parseArrayWhereItems($field, $logic);
        } elseif ($field instanceof \Closure) {
            $where = $field;
        } elseif (is_string($field)) {
            if (preg_match('/[,=\<\'\"\(\s]/', $field)) {
                return $this->whereRaw($field, $op);
            } elseif (is_string($op) && strtolower($op) == 'exp') {
                $bind = isset($param[2]) && is_array($param[2]) ? $param[2] : null;
                return $this->whereExp($field, $condition, $bind, $logic);
            }

            $where = $this->parseWhereItem($logic, $field, $op, $condition, $param);
        }

        if (!empty($where)) {
            $this->options['where'][$logic][] = $where;
        }

        return $this;
    }

    public function getAlias()
    {
        $table = $this->getTable();
        if(isset($this->options['alias'][$table])){
            return $this->options['alias'][$table];
        }else{
            return $table;
        }
    }

}