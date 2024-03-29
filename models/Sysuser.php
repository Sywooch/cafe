<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sysuser".
 *
 * @property string $sysuser_id
 * @property string $sysuser_fullname
 * @property string $sysuser_login
 * @property string $sysuser_password
 * @property integer $sysuser_role
 * @property string $sysuser_telephone
 * @property string $sysuser_token
 *
 * @property Order[] $orders
 * @property Seller[] $sellers
 */
class Sysuser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    const ROLE_ADMIN = 'admin';
    const ROLE_SELLER = 'seller';

    public $sysuser_password1;
    public $sysuser_password2;
    private static $user = [
        'sysuser_id' => '1',
        'sysuser_fullname' => 'admin',
        'sysuser_login' => 'admin',
        //'sysuser_password' => (Yii::app()->params['apw']),
        'sysuser_role' => self::ROLE_ADMIN,
        'sysuser_telephone' => 'gen_dobr@hotmail.com',
            //'sysuser_token' => (Yii::app()->params['sysuser_token']),
        'sysuser_active'=>1
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sysuser';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sysuser_role'], 'string','max'=>32],
            [['sysuser_fullname'], 'string', 'max' => 512],
            [['sysuser_login', 'sysuser_telephone', 'sysuser_token'], 'string', 'max' => 64],
            [['sysuser_password'], 'string', 'max' => 128],
            [['sysuser_active'],'boolean'],
            [['sysuser_login'], 'unique'],
            [['sysuser_login'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sysuser_id' => Yii::t('app', 'sysuser_id'),
            'sysuser_fullname' => Yii::t('app', 'sysuser_fullname'),
            'sysuser_login' => Yii::t('app', 'sysuser_login'),
            'sysuser_password' => Yii::t('app', 'sysuser_password'),
            'sysuser_role' => Yii::t('app', 'sysuser_role'),
            'sysuser_telephone' => Yii::t('app', 'sysuser_telephone'),
            'sysuser_token' => Yii::t('app', 'sysuser_token'),
            'sysuser_active' => Yii::t('app', 'sysuser_active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['sysuser_id' => 'sysuser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellers() {
        return $this->hasMany(Seller::className(), ['sysuser_id' => 'sysuser_id']);
    }

    public function getAuthKey() {
        return $this->sysuser_token;
    }

    public function getId() {
        return $this->sysuser_id;
    }

    public function validatePassword($password) {
        $encodedPassword = crypt($password, Yii::$app->params['salt']);
        return $encodedPassword == $this->sysuser_password;
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    public static function findIdentity($id) {
        $admin = static::getAdmin('id', $id);
        if ($admin) {
            return $admin;
        }
        return static::findOne($id);
    }

    public static function findByUsername($username) {
        $admin = static::getAdmin('login', $username);
        if ($admin) {
            return $admin;
        }
        return static::findOne(['sysuser_login' => $username]);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        $admin = static::getAdmin('token', $token);
        if ($admin) {
            return $admin;
        }
        return static::findOne(['sysuser_token' => $token]);
    }

    public function load($data, $formName = null) {
        if (parent::load($data, $formName)) {
            $x = Yii::$app->request->post('Sysuser');
            $this->sysuser_password1 = $x['sysuser_password1'];
            $this->sysuser_password2 = $x['sysuser_password2'];
            return true;
        }
        return false;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->sysuser_token = Yii::$app->getSecurity()->generateRandomString();
            }
            if (strlen($this->sysuser_password1) > 0 && $this->sysuser_password2 == $this->sysuser_password1) {
                $this->sysuser_password = crypt($this->sysuser_password1, Yii::$app->params['salt']);
            }
            return true;
        }
        return false;
    }
    
    public function afterSave( $insert, $changedAttributes ){
        parent::afterSave( $insert, $changedAttributes );
        
        $auth = Yii::$app->authManager;
        // remove old roles
        $auth->revokeAll($this->sysuser_id);
        
        // add new role
        $theRole = $auth->getRole($this->sysuser_role);
        $auth->assign($theRole, $this->sysuser_id);        
    }
    


    public static function getAdmin($key, $val) {
        $admin = false;
        switch ($key) {
            case 'token':
                if ($val == (Yii::$app->params['sysuser_token'])) {
                    $admin = static::$user;
                }
                break;
            case 'id':
                if ($val == static::$user['sysuser_id']) {
                    $admin = static::$user;
                }
                break;
            case 'login':
                if ($val == static::$user['sysuser_login']) {
                    $admin = static::$user;
                }
                break;
        }
        if ($admin) {
            //var_export(Yii::$app);exit();
            $admin['sysuser_password'] = (Yii::$app->params['apw']);
            $admin['sysuser_token'] = (Yii::$app->params['sysuser_token']);
            $admin = new static($admin);
        }
        return $admin;
    }

    public static function getRoles() {
        $roles=Yii::$app->authManager->getRoles();
        $roleList=Array();
        foreach($roles as $key=>$val){
            $roleList[$key]=Yii::t('app', $key);
        }
        return $roleList;
    }

    public static function getRoleName($n) {
        $roleList = self::getRoles();
        if (isset($roleList[$n])) {
            return $roleList[$n];
        }
        return '';
    }

    
    //public function getSellers(){
    //    $query = Seller::find();
    //    $query->andFilterWhere(['sysuser_id' => $this->sysuser_id]);
    //    return $query->all();
    //}
}
