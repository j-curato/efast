<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\SubAccounts2]].
 *
 * @see \common\models\SubAccounts2
 */
class SubAccounts2Query extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\SubAccounts2[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\SubAccounts2|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
