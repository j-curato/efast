<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jev_reporting_period}}`.
 */
class m210512_034225_create_jev_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jev_reporting_period}}', [
            'id' => $this->primaryKey(),
            'reporting_period' => $this->string(20)->notNull(),
            'is_disabled' => $this->boolean()->defaultValue(true),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%jev_reporting_period}}');
    }
}
