<?php
declare(strict_types=1);

use e2221\HtmElement\BaseElement;
use PHPMailer\PHPMailer\PHPMailer;

class Message
{
    private string $smtpHost;
    private string $smtpUser;
    private string $smtpPassword;
    protected ?string $senderEmail;
    protected string $smtpSecure = 'tls';
    protected int $port = 587;
    public string $subject = '';
    protected ?string $receiverEmail = null;

    /**
     * Message constructor.
     * @param string $smtpHost
     * @param string $smtpUser
     * @param string $smtpPassword
     * @param string|null $senderEmail
     * @param string|null $receiverEmail
     */
    public function __construct(
        string $smtpHost,
        string $smtpUser,
        string $smtpPassword,
        ?string $senderEmail=null,
        ?string $receiverEmail=null)
    {
        $this->smtpHost = $smtpHost;
        $this->smtpUser = $smtpUser;
        $this->smtpPassword = $smtpPassword;
        $this->senderEmail = $senderEmail;
        $this->receiverEmail = $receiverEmail;
    }


    /**
     * Load mailer
     * @return PHPMailer
     */
    protected function loadMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $this->smtpHost;
        $mail->Username = $this->smtpUser;
        $mail->Password = $this->smtpPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        return $mail;

    }

    /**
     * Message builder
     * @param Order $order
     * @return string
     */
    protected function buildMessage(Order $order): string
    {
        $table = BaseElement::getStatic('table');
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Jméno a příjmení:'))
            ->addElement(BaseElement::getStatic('td', [], sprintf('%s %s', $order->getFirstName(), $order->getLastName())));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Ulice a čp.:'))
            ->addElement(BaseElement::getStatic('td', [], $order->getStreet()));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Město:'))
            ->addElement(BaseElement::getStatic('td', [], $order->getTown()));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'PSČ:'))
            ->addElement(BaseElement::getStatic('td', [], $order->getZipCode()));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Telefon:'))
            ->addElement(BaseElement::getStatic('td', [], $order->getPhoneNumber()));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'E-mail:'))
            ->addElement(BaseElement::getStatic('td', [], $order->getEmail()));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Množství:'))
            ->addElement(BaseElement::getStatic('td', [], sprintf("%s ks", $order->getQuantity())));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Doprava:'))
            ->addElement(BaseElement::getStatic('td', [], $order->getTransport()));
        $tr[] = BaseElement::getStatic('tr')
            ->addElement(BaseElement::getStatic('th', [], 'Cena celkem:'))
            ->addElement(BaseElement::getStatic('td', [], sprintf("%s Kč", $order->sumFullPrice())));
        foreach($tr as $trKey => $trEl)
            $table->addElement($trEl);

        $html = BaseElement::getStatic('html', ['lang'=>'cs'])
            ->addElement(
                BaseElement::getStatic('body')
                    ->addElement(BaseElement::getStatic('h2', [], config::EMAIL_ORDER_SUBJECT))
                    ->addElement($table)
                    ->addElement(BaseElement::getStatic('br'))
                    ->addElement(
                        BaseElement::getStatic()
                            ->setTextContent(sprintf('Vygenerováno %s', date('j.n.Y H:i:s')))
                    )
            );
        return $html->render()->toHtml();
    }

    /**
     * Send message
     * @param Order $order
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send(Order $order): void
    {
        $mail = $this->loadMailer();
        $mail->setFrom($this->senderEmail ?? $this->smtpUser);
        if(is_string($this->receiverEmail))
            $mail->addAddress($this->receiverEmail);
        $mail->isHTML();
        $mail->Subject = $this->subject;
        $mail->Body = $this->buildMessage($order);
        $mail->send();
    }

    /**
     * Set smtp secure
     * @param string $smtpSecure
     * @return Message
     */
    public function setSmtpSecure(string $smtpSecure): self
    {
        $this->smtpSecure = $smtpSecure;
        return $this;
    }

    /**
     * Set port
     * @param int $port
     * @return Message
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Set subject
     * @param string $subject
     * @return Message
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set receiver email
     * @param string $receiverEmail
     * @return Message
     */
    public function setReceiverEmail(string $receiverEmail): self
    {
        $this->receiverEmail = $receiverEmail;
        return $this;
    }
}