<?php

namespace core\useCases;

use yii\mail\MailerInterface;
use RuntimeException;
use core\forms\ContactForm;

class ContactService
{
    private $adminEmail;
    private $mailer;

    public function __construct($adminEmail, MailerInterface $mailer)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    public function send(ContactForm $form): void
    {
        $sent = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setSubject('Письмо от ' . $form->username)
            ->setTextBody($form->message)
            ->send();

        if (!$sent) {
            throw new RuntimeException('Возникла ошибка');
        }
    }
}