<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_aucs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%process_ors}}`
 * - `{{%raouds}}`
 */
class m210326_080246_create_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_aucs}}', [
            'id' => $this->primaryKey(),
            'process_ors_id' => $this->integer(),
            'raoud_id' => $this->integer(),
            'dv_number'=>$this->string(),
            'reporting_period'=>$this->string(50),
            'tax_withheld'=>$this->string(),
            'other_trust_liability_withheld'=>$this->string(),
            'net_amount_paid'=>$this->float(),
            
        ]);

        // creates index for column `process_ors_id`
        $this->createIndex(
            '{{%idx-dv_aucs-process_ors_id}}',
            '{{%dv_aucs}}',
            'process_ors_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs-process_ors_id}}',
            '{{%dv_aucs}}',
            'process_ors_id',
            '{{%process_ors}}',
            'id',
            'CASCADE'
        );

        // creates index for column `raoud_id`
        $this->createIndex(
            '{{%idx-dv_aucs-raoud_id}}',
            '{{%dv_aucs}}',
            'raoud_id'
        );

        // add foreign key for table `{{%raouds}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs-raoud_id}}',
            '{{%dv_aucs}}',
            'raoud_id',
            '{{%raouds}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%process_ors}}`
        $this->dropForeignKey(
            '{{%fk-dv_aucs-process_ors_id}}',
            '{{%dv_aucs}}'
        );

        // drops index for column `process_ors_id`
        $this->dropIndex(
            '{{%idx-dv_aucs-process_ors_id}}',
            '{{%dv_aucs}}'
        );

        // drops foreign key for table `{{%raouds}}`
        $this->dropForeignKey(
            '{{%fk-dv_aucs-raoud_id}}',
            '{{%dv_aucs}}'
        );

        // drops index for column `raoud_id`
        $this->dropIndex(
            '{{%idx-dv_aucs-raoud_id}}',
            '{{%dv_aucs}}'
        );

        $this->dropTable('{{%dv_aucs}}');
    }
}
