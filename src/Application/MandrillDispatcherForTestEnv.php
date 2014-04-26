<?php

namespace Application;

use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;

class MandrillDispatcherForTestEnv extends Dispatcher
{
    public function send(Message $message, $templateName = '', $templateContent = array(), $async = false)
    {
        //ToDo: add write log message functionality
    }
}
