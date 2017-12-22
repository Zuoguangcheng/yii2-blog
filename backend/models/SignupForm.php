<?php

namespace backend\models;

use common\models\Adminuser;
use yii\base\Model;
use common\models\User;
use yii\helpers\VarDumper;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nickname;
    public $password_repeat;
    public $profile;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\Adminuser', 'message' => '用户名重复复'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Adminuser', 'message' => '邮箱地址重复'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不同'],
            ['nickname', 'required'],

            ['nickname', 'string', 'max' => 128]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '管理员名称',
            'nickname' => '昵称',
            'password' => '密码',
            'password_repeat' => '重输密码',
            'email' => 'Email',
            'profile' => '简介',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Adminuser();
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->email = $this->email;
        $user->profile = $this->profile;
        $user->password = '*';
        $user->setPassword($this->password);
        $user->generateAuthKey();

//        $user->save();
//        VarDumper::dump($user->errors);
//        exit(0);

        return $user->save() ? $user : null;
    }
}
