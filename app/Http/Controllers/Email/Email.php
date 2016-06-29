<?php
namespace IAServer\Http\Controllers\Email;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class Email extends Controller
{
    public $from = 'aoi.collector@newsan.com.ar';
    public $name = 'IAServer';

    public function __construct($from=null,$name=null)
    {
        if($from!=null)
        {
            $this->from = $from;
        }

        if($name!=null)
        {
            $this->name= $name;
        }
    }

    public function send($toUser=null, $toMail=null, $subject="IAServer", $viewData=array(), $view='emails.default')
    {
        Mail::send($view, $viewData, function ($m) use ($toUser, $toMail, $subject) {
            $m->from($this->from, $this->name);
            $m->to($toMail, $toUser)->subject($subject);
        });
    }
}