<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fund_source_type}}`.
 */
class m210727_023345_create_fund_source_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fund_source_type}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fund_source_type}}');
    }
}
