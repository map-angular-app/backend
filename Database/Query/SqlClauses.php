<?php


namespace Database\Query;

trait SqlClauses
{
  /**
   * Select clause parameters.
   *
   * @var array
   */
  protected $select = [];

  /**
   * Select raw clause parameters.
   *
   * @var array
   */
  protected $selectRaw = [];

  /**
   * From clause parameters.
   *
   * @var array
   */
  protected $from = [];

  /**
   * Where clause parameters.
   *
   * @var array
   */
  protected $where = [];

  /**
   * Group By clause parameters.
   *
   * @var array
   */
  protected $groupBy = [];

  /**
   * Having clause parameters.
   *
   * @var array
   */
  protected $having = [];

  /**
   * Order By clause parameters.
   *
   * @var array
   */
  protected $orderBy = [];

  /**
   * Limit Clause parameters.
   *
   * @var integer
   */
  protected $limit;

  /**
   * Offset clause parameters.
   *
   * @var integer
   */
  protected $offset;

  /**
   * Apply select clause.
   *
   * @param  mixed  $fields
   * @return $this
   */
  public function select($fields = ['*'])
  {
    $fields = is_array($fields) ? $fields : func_get_args();

    $this->addSelect($fields);

    return $this;
  }

  /**
   * Apply select clause.
   *
   * @param  mixed  $fields
   * @return $this
   */
  public function selectRaw($fields = ['*'])
  {
    $fields = is_array($fields) ? $fields : func_get_args();
    $this->addSelectRaw($fields);
    return $this;
  }

  /**
   * Apply from clause.
   *
   * @param  mixed  $tables
   * @return $this
   */
  public function from($tables)
  {
    $tables = is_array($tables) ? $tables : func_get_args();

    $this->addFrom($tables);

    return $this;
  }

  /**
   * Apply where clause.
   *
   * @param  array  $params
   * @return $this
   */
  public function where(...$params)
  {
    return $this->andWhere(...$params);
  }

  /**
   * Apply where clause with and.
   *
   * @param  array  $params
   * @return $this
   */
  public function andWhere(...$params)
  {
    return $this->whereLogicOperator('and', ...$params);
  }

  /**
   * Apply where clause with or.
   *
   * @param  array  $params
   * @return $this
   */
  public function orWhere(...$params)
  {
    return $this->whereLogicOperator('or', ...$params);
  }
  public function paginate($page, $perpage)
  {
    $this->limit = $perpage;
    $this->offset =  ($page - 1) * $perpage;
  }
  public function orderBy($orderBy, $desc)
  {
    $this->orderBy[] = ['field' => $orderBy, 'desc' => $desc];
  }
  public function groupBy($groupBy)
  {
    $this->groupBy[] = ['field' => $groupBy];
    return $this;
  }
  /**
   * Apply having clause.
   *
   * @param  array  $params
   * @return $this
   */
  public function having(...$params)
  {
    return $this->andHaving(...$params);
  }
  /**
   * Apply having clause with and.
   *
   * @param  array  $params
   * @return $this
   */
  public function andHaving(...$params)
  {
    return $this->havingLogicOperator('and', ...$params);
  }
  public function orHaving(...$params)
  {
    return $this->havingLogicOperator('or', ...$params);
  }
}
