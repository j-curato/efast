<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%liquidation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%payee}}`
 * - `{{%responsibility_center}}`
 */
class m210507_005849_create_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%liquidation}}', [
            'id' => $this->primaryKey(),
            'payee_id' => $this->integer(),
            'responsibility_center_id' => $this->integer(),
            'check_date'=>$this->string(50),
            'check_number'=>$this->string(50),
            'dv_number'=>$this->string(100),
            'particular'=>$this->text(),


        ]);

        // creates index for column `payee_id`
        $this->createIndex(
            '{{%idx-liquidation-payee_id}}',
            '{{%liquidation}}',
            'payee_id'
        );

        // add foreign key for table `{{%payee}}`
        $this->addForeignKey(
            '{{%fk-liquidation-payee_id}}',
            '{{%liquidation}}',
            'payee_id',
            '{{%payee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `responsibility_center_id`
        $this->createIndex(
            '{{%idx-liquidation-responsibility_center_id}}',
            '{{%liquidation}}',
            'responsibility_center_id'
        );

        // add foreign key for table `{{%responsibility_center}}`
        $this->addForeignKey(
            '{{%fk-liquidation-responsibility_center_id}}',
            '{{%liquidation}}',
            'responsibility_center_id',
            '{{%responsibility_center}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%payee}}`
        $this->dropForeignKey(
            '{{%fk-liquidation-payee_id}}',
            '{{%liquidation}}'
        );

        // drops index for column `payee_id`
        $this->dropIndex(
            '{{%idx-liquidation-payee_id}}',
            '{{%liquidation}}'
        );

        // drops foreign key for table `{{%responsibility_center}}`
        $this->dropForeignKey(
            '{{%fk-liquidation-responsibility_center_id}}',
            '{{%liquidation}}'
        );

        // drops index for column `responsibility_center_id`
        $this->dropIndex(
            '{{%idx-liquidation-responsibility_center_id}}',
            '{{%liquidation}}'
        );

        $this->dropTable('{{%liquidation}}');
    }
}
