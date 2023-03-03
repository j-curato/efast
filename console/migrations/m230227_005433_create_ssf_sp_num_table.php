<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ssf_sp_num}}`.
 */
class m230227_005433_create_ssf_sp_num_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ssf_sp_num}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'budget_year' => $this->integer()->notNull(),
            'fk_office_id' => $this->integer()->notNull(),
            'fk_citymun_id' => $this->integer()->notNull(),
            'project_name' => $this->text()->notNull(),
            'cooperator' => $this->string()->notNull(),
            'equipment' => $this->text()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'date' => $this->date()->notNull(),
            'status' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('ssf_sp_num', 'id', $this->bigInteger());
        $this->createIndex('idx-fk_office_id', 'ssf_sp_num', 'fk_office_id');
        $this->createIndex('idx-fk_citymun_id', 'ssf_sp_num', 'fk_citymun_id');
        $this->addForeignKey('fk-ssf-sp-fk_office_id', 'ssf_sp_num', 'fk_office_id', 'office', 'id', 'RESTRICT');
        $this->addForeignKey('fk-ssf-sp-fk_citymun_id', 'ssf_sp_num', 'fk_citymun_id', 'citymun', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ssf-sp-fk_office_id', 'ssf_sp_num');
        $this->dropForeignKey('fk-ssf-sp-fk_citymun_id', 'ssf_sp_num');
        $this->dropIndex('idx-fk_office_id', 'ssf_sp_num');
        $this->dropIndex('idx-fk_citymun_id', 'ssf_sp_num');
        $this->dropTable('{{%ssf_sp_num}}');
    }
}
