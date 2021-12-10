<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_aucs_disable_reporting_period}}`.
 */
class m211210_020043_create_dv_aucs_disable_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_aucs_disable_reporting_period}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(25),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dv_aucs_disable_reporting_period}}');
    }
}
