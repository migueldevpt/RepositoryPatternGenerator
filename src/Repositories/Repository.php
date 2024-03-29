<?php

namespace Paulo\Repositories;

use Paulo\Exceptions\RepositoryException;
use Paulo\Exceptions\ResultNotFoundException;

trait Repository
{
    /**
     * @var string model.
     */
    private $model;

    /**
     * @var array allowed actions.
     */
    private $actions = ['*'];

    /**
     * Get's by it's ID
     *
     * @param int $id
     */
    public function get(int $id)
    {
        $this->decorateAction('get');

        return $this->model::find($id);
    }

    /**
     * Get's all.
     *
     * @return mixed
     */
    public function all()
    {
        $this->decorateAction('all');

        return $this->model::all();
    }

    /**
     * Stores.
     *
     * @param array $data
     */
    public function store(array $data)
    {
        $this->decorateAction('store');

        return $this->model::create($data);
    }

    /**
     * Deletes.
     *
     * @param int
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->decorateAction('delete');

        $this->model::destroy($id);
    }

    /**
     * Updates.
     *
     * @param int   $id
     * @param array $data
     *
     * @throws ResultNotFoundException
     */
    public function update(int $id, array $data)
    {
        $this->decorateAction('update');

        $result = $this->model::find($id);

        if (empty($result)) {
            throw new ResultNotFoundException(new $this->model, $id);
        }

        return $result->update($data);
    }

    /**
     * Check if the current called action in in available actions.
     *
     * @param string $action current called method.
     *
     * @throws \App\Exceptions\RepositoryException
     *
     * @return void
     */
    private function decorateAction(string $action = '*')
    {
        if ($this->actions[0] != '*' && !in_array($action, $this->actions)) {
            throw new RepositoryException($action);
        }
    }
}
