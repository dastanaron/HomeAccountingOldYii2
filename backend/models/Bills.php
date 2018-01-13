<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Balance;

/**
 * This is the model class for table "bills".
 *
 * @property int $id
 * @property int $balance_id
 * @property string $name
 * @property int $sum
 * @property string $deadline
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Balance $balance
 */
class Bills extends ActiveRecord
{

    /**
     * Funds constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->balance_id = $this->getBalanceId();
    }

    /**
     * @return mixed
     */
    private function getBalanceId()
    {
        $user = Yii::$app->user->identity->getId();
        $balance = Balance::find()->where(['user_id' => $user])->one();
        return $balance->id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bills';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance_id', 'name'], 'required'],
            [['balance_id', 'sum'], 'integer'],
            [['deadline', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['balance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Balance::className(), 'targetAttribute' => ['balance_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balance_id' => 'Связка с балансом',
            'name' => 'Название',
            'sum' => 'Сумма',
            'deadline' => 'Срок окончания сберегательной программы',
            'created_at' => 'Дата создания',
            'updated_at' => 'Последнее обновление',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalance()
    {
        return $this->hasOne(Balance::className(), ['id' => 'balance_id']);
    }
}
