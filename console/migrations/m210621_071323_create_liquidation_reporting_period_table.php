<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%liquidation_reporting_period}}`.
 */
class m210621_071323_create_liquidation_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%liquidation_reporting_period}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(50),
            'is_locked'=>$this->boolean()->defaultValue(false)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%liquidation_reporting_period}}');
    }
}
