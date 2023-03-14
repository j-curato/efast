<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rlsddp}}`.
 */
class m230313_065232_create_rlsddp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rlsddp}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'fk_acctbl_offr' => $this->bigInteger()->notNull(),
            'is_blottered' => $this->boolean()->defaultValue(0)->notNull(),
            'blotter_date' => $this->date()->notNull(),
            'police_station' => $this->string(),
            'fk_property_status_id' => $this->integer()->notNull(),
            'fk_supvr' => $this->bigInteger()->notNull(),
            'fk_office_id' => $this->integer()->notNull(),
            'circumstances' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);
        $this->alterColumn('rlsddp', 'id', $this->bigInteger());
        $this->createIndex('idx-fk_acctbl_offr', 'rlsddp', 'fk_acctbl_offr');
        $this->createIndex('idx-fk_property_status_id', 'rlsddp', 'fk_property_status_id');
        $this->createIndex('idx-fk_supvr', 'rlsddp', 'fk_supvr');

        $this->addForeignKey('fk-rlsddp-fk_acctbl_offr', 'rlsddp', 'fk_acctbl_offr', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-rlsddp-fk_supvr', 'rlsddp', 'fk_supvr', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-rlsddp-fk_property_status_id', 'rlsddp', 'fk_property_status_id', 'property_status', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey('fk-rlsddp-fk_acctbl_offr', 'rlsddp');
        $this->dropForeignKey('fk-rlsddp-fk_supvr', 'rlsddp');
        $this->dropForeignKey('fk-rlsddp-fk_property_status_id', 'rlsddp');

        $this->dropIndex('idx-fk_acctbl_offr', 'rlsddp');
        $this->dropIndex('idx-fk_property_status_id', 'rlsddp');
        $this->dropIndex('idx-fk_supvr', 'rlsddp');

        $this->dropTable('{{%rlsddp}}');
    }
}
