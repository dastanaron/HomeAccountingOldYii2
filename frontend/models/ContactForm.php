<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'E-mail',
            'subject' => 'Тема сообщения',
            'body' => 'Текст сообщения',
            'verifyCode' => 'Докажи что не робот',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        if(empty($this->subject)) {
            $this->subject = 'Без темы';
        }

        $body = '
        <table border="1" style="border-collapse: collapse">
            <tr><td>Имя:</td><td>'.$this->name.'</td></tr>
            <tr><td>E-mail:</td><td>'.$this->email.'</td></tr>
            <tr><td>Сообщение:</td><td>'.$this->body.'</td></tr>
        </table>
        ';

        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom('flow199@yandex.ru')
            ->setSubject($this->subject)
            ->setHtmlBody($body)
            ->send();
    }
}
