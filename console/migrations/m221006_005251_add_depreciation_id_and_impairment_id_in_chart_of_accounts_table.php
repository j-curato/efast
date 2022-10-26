<?php

use yii\db\Migration;

/**
 * Class m221006_005251_add_depreciation_id_and_impairment_id_in_chart_of_accounts_table
 */
class m221006_005251_add_depreciation_id_and_impairment_id_in_chart_of_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chart_of_accounts', 'fk_depreciation_id', $this->integer());
        $this->addColumn('chart_of_accounts', 'fk_impairment_id', $this->integer());


        $this->createIndex(
            '{{%idx-chart_of_accounts-fk_depreciation_id}}',
            '{{%chart_of_accounts}}',
            'fk_depreciation_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        // $this->addForeignKey(
        //     '{{%fk-chart_of_accounts-fk_depreciation_id}}',
        //     '{{%chart_of_accounts}}',
        //     'fk_depreciation_id',
        //     '{{%chart_of_accounts}}',
        //     'id'
        // );
        // $this->createIndex(
        //     '{{%idx-chart_of_accounts-fk_impairment_id}}',
        //     '{{%chart_of_accounts}}',
        //     'fk_impairment_id'
        // );

        // // add foreign key for table `{{%dv_aucs}}`
        // $this->addForeignKey(
        //     '{{%fk-chart_of_accounts-fk_impairment_id}}',
        //     '{{%chart_of_accounts}}',
        //     'fk_impairment_id',
        //     '{{%chart_of_accounts}}',
        //     'id'
        // );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-chart_of_accounts-fk_impairment_id}}',
            '{{%chart_of_accounts}}'
        );

        // drops index for column `fk_dv_aucs_id`
        $this->dropIndex(
            '{{%idx-chart_of_accounts-fk_impairment_id}}',
            '{{%chart_of_accounts}}'
        );
        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-chart_of_accounts-fk_depreciation_id}}',
            '{{%chart_of_accounts}}'
        );

        // drops index for column `fk_depreciation_id`
        $this->dropIndex(
            '{{%idx-chart_of_accounts-fk_depreciation_id}}',
            '{{%chart_of_accounts}}'
        );
        $this->dropColumn('chart_of_accounts', 'fk_depreciation_id');
        $this->dropColumn('chart_of_accounts', 'fk_impairment_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221006_005251_add_depreciation_id_and_impairment_id_in_chart_of_accounts_table cannot be reverted.\n";

        return false;
    }
    */
}
