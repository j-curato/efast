<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bac_position}}`.
 */
class m220113_023728_create_bac_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bac_position}}', [
            'id' => $this->primaryKey(),
            'position'=>$this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bac_position}}');
    }
}
