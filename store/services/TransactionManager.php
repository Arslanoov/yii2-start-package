<?php

namespace store\services;

use Yii;
use Exception;

class TransactionManager
{
    public function wrap(callable $function): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $function();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}