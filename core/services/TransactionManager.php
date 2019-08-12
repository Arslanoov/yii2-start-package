<?php

namespace core\services;

use Yii;
use core\dispatchers\DeferredEventDispatcher;
use Exception;

class TransactionManager
{
    private $dispatcher;

    public function __construct(DeferredEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param callable $function
     * @throws \Throwable
     */
    public function wrap(callable $function): void
    {
        Yii::$app->db->transaction($function);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->dispatcher->defer();
            $function();
            $transaction->commit();
            $this->dispatcher->release();
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->dispatcher->clean();
            throw $e;
        }
    }
}