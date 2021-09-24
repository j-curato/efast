<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report_type}}`.
 */
class m210924_092253_create_report_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%report_type}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%report_type}}');
    }
}
