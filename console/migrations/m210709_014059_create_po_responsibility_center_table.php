<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_responsibility_center}}`.
 */
class m210709_014059_create_po_responsibility_center_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_responsibility_center}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'description'=>$this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%po_responsibility_center}}');
    }
}
