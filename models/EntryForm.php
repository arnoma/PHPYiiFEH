<?php
    namespace app\models;
    use Yii;
    use yii\base\Model;

    class EntryForm extends Model {


        public $name;
        public $email;

        function rules()
        {
            return [
              [['name','email'],'required'], //email 和 name 是必须的
                ['email','email'], //email必须符合email的验证规则
            ];
        }
    }
