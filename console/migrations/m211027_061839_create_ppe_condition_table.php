<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ppe_condition}}`.
 */
class m211027_061839_create_ppe_condition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ppe_condition}}', [
            'id' => $this->primaryKey(),
            'condition'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ppe_condition}}');
    }
}
