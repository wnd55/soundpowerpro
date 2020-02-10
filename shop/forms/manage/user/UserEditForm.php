<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 14:31
 */


namespace shop\forms\manage\user;


use Yii;
use shop\entities\user\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserEditForm extends Model
{

    public $username;
    public $email;
    public $role;
    public $_user;


    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;


        parent::__construct($config);
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['username', 'email',], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }

    public function rolesList()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }


}