<?php
namespace Akmb\Core\Controllers;

class ErrorController extends DefaultController
{
    public function internalError($msg): string
    {
        $this->setInternalServerErrorHeaders();

        //set status code 500
        //need extra log?
        return $this->render($msg);
    }

    private function setInternalServerErrorHeaders()
    {
        $server = $this->request->getServer();
        header($server['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    }
}