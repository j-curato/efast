<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ro_alphalist}}`.
 */
class m220520_002742_create_ro_alphalist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ro_alphalist}}', [
            'id' => $this->primaryKey(),
            'alphalist_number' => $this->string()->unique()->notNull(),
            'reporting_period'=>$this->string(20),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('ro_alphalist', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ro_alphalist}}');
    }
}
