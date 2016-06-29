<?php
namespace IAServer\Http\Controllers\Email;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class EmailView extends Controller
{
    public function sendByGet($to,$toMail,$subject,$data)
    {
        $params = [
            'to' => $to,
            "mail" => $toMail,
            "subject" => $subject,
            "data" => $data
        ];

        $json = (object) $params;
        return $this->handleSent($json);
    }

    public function send()
    {
        $request = Request::instance();
        $content = $request->getContent();

        $json = json_decode($content);
        $json = $json->data;

        return $this->handleSent($json);
    }

    private function handleSent($json)
    {
        if(isset($json->to) && isset($json->mail) && isset($json->subject) && isset($json->data))
        {
            if(!isset($json->vista))
            {
                $json->vista = 'emails.default';
            }

            $mail = new Email();
            $mail->send($json->to,$json->mail,$json->subject,['data'=>$json->data],$json->vista);

            return "Enviado a ".$json->mail;
        } else
        {
            return "
            No definieron los parametros necesarios.
            EJ:
            <pre>
                {
                    \"to\" : \"Matias\",
                    \"mail\" : \"matius77@gmail.com\",
                    \"subject\" : \"IAServer MailService\",
                    \"data\" : 12345
                }
            </pre>
            ";
        }
    }
}