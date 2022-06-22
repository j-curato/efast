<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pre_repair_inspection}}`.
 */
class m220621_010631_create_pre_repair_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pre_repair_inspection}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'date' => $this->date(),
            'findings' => $this->text(),
            'recommendation' => $this->text(),
            'fk_requested_by' => $this->bigInteger(),
            'fk_accountable_person'=>$this->bigInteger(),
            'equipment_type'=>$this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);
        $this->alterColumn('pre_repair_inspection', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pre_repair_inspection}}');
    }
}
