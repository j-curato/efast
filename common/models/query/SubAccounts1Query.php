<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\SubAccounts1]].
 *
 * @see \common\models\SubAccounts1
 */
class SubAccounts1Query extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\SubAccounts1[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\SubAccounts1|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
