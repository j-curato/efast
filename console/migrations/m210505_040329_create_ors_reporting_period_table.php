<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ors_reporting_period}}`.
 */
class m210505_040329_create_ors_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ors_reporting_period}}', [
            'id' => $this->primaryKey(),
            'reporting_period' => $this->string(),
            'disabled' => $this->boolean()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ors_reporting_period}}');
    }
}
