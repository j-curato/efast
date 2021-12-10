<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%record_allotment_disable_reporting_period}}`.
 */
class m211210_015951_create_record_allotment_disable_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%record_allotment_disable_reporting_period}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(20),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%record_allotment_disable_reporting_period}}');
    }
}
