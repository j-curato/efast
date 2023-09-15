<?php

use yii\db\Migration;

/**
 * Class m220505_050002_alter_id_in_transaction_table
 */
class m220505_050002_alter_id_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0;")->query();

        $sql = <<<SQL

        -- ALTER TABLE `process_burs` DROP FOREIGN KEY `fk-process_burs-transaction_id`;
        -- ALTER TABLE `process_ors` DROP FOREIGN KEY IF EXISTS `fk-process_ors-transaction_id`;
        SQL;
        $this->execute($sql);

        $this->dropIndex(
            '{{%idx-process_burs-transaction_id}}',
            '{{%process_burs}}'
        );

        $this->dropIndex(
            '{{%idx-process_ors-transaction_id}}',
            '{{%process_ors}}'
        );


        $this->alterColumn('transaction', 'id', $this->bigInteger());
        $this->alterColumn('process_ors', 'transaction_id', $this->bigInteger());
        $this->alterColumn('process_burs', 'transaction_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // creates index for column `transaction_id`
        $this->createIndex(
            '{{%idx-process_burs-transaction_id}}',
            '{{%process_burs}}',
            'transaction_id'
        );


        $this->createIndex(
            '{{%idx-process_ors-transaction_id}}',
            '{{%process_ors}}',
            'transaction_id'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220505_050002_alter_id_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
