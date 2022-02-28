<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 27/02/22
 * Time: 02:54 م
 */

namespace App\Component\Mailer;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

abstract class QaMailer
{
    /**
     * @var MailerInterface $mailer
     */
    protected $mailer;
    /**
     * @var callable
     */
    private $mailComposer;

    /**
     * QaMailer constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @param callable $mailComposer
     * @return QaMailer
     */
    protected function setMailComposer(callable $mailComposer): QaMailer
    {
        $this->mailComposer = $mailComposer;
        return $this;
    }

    protected function send(){
        $email = new Email();
        call_user_func($this->mailComposer, $email);
        $this->mailer->send($email);
    }






}
