<?php

namespace App;

/**
 * E-mail Sender
 *
 * @author Paul Melekhov
 */
class EmailSender
{
    /**
     * @var array
     */
    private $_config;

    /**
     * @var TemplateEngine
     */
    private $_tpl;

    public function __construct(array $config, \App\TemplateEngine $tpl)
    {
        $this->_config = $config;
        $this->_tpl = $tpl;
    }

    /**
     * Отправляет е-мейл с телом письма по шаблону и приаттаченными файлами
     *
     * @param string $email
     * @param string $template
     * @param array $params
     * @param array $files      массив из хэшей с ключами:
     *                              "file" - путь к отправляемому файлу
     *                              "mime" - MIME-тип файла
     *                              "name" - публичное имя файла
     * @throws Exception
     * @return void
     */
    public function send($email, $template, $params, array $files = array())
    {
        if (APPLICATION_ENV != "production") {
            if (! empty($this->_config["development"]["email"])) {
                $email = $this->_config["development"]["email"];
            } else {
                return;
            }
        }

        $mail = new \Zend_Mail('UTF-8');

        $contents = $this->_tpl->render($template, $params);
        $contentsParts = preg_split('|\n\r?\n\r?|', $contents, 2);
        list($headersPart, $bodyPart) = count($contentsParts) == 2 ? $contentsParts : array("", $contents);
        foreach (explode("\n", $headersPart) as $headerLine) {
            $headerLineParts = preg_split('|\s*:\s*|', rtrim($headerLine), 2);
            if (count($headerLineParts) != 2) {
                throw new Exception("Invalid header line '".rtrim($headerLine, "\r\n")."' in email template '{$template}'");
            }
            list($headerName, $headerValue) = $headerLineParts;
            switch (strtolower($headerName)) {
                case "subject" :
                    $mail->setSubject($headerValue);
                    break;
                case 'to' :
                case 'cc' :
                case 'bcc' :
                case 'from' :
                case 'reply-to' :
                case 'return-path' :
                case 'date' :
                case 'message-id' :
                    throw new \App\Exception("Not implemented feature");
                default :
                    $mail->addHeader($headerName, $headerValue);
            }
        }

        // From:
        $mail->setFrom($this->_config["email"]["fromEmail"], $this->_config["email"]["fromName"]);

        // To:
        $mail->addTo($email);

        // Body:
        $mail->setBodyHtml($bodyPart);

        // File Attachments
        foreach ($files as $file) {
            $this->_addFile($mail, $file["file"],
                isset($file["mime"]) ? $file["mime"] : \Zend_Mime::TYPE_OCTETSTREAM,
                isset($file["name"]) ? $file["name"] : null
            );
        }

        $mail->send();
    }

    private function _addFile(\Zend_Mail $mail, $pathToFile, $mimeType, $publicFileName)
    {
        $mail->createAttachment(
            file_get_contents($pathToFile),
            $mimeType,
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            $publicFileName ? $publicFileName : basename($pathToFile)
        );
    }

}
