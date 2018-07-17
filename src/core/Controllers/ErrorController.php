<?php
namespace Akmb\Core\Controllers;

class ErrorController extends DefaultController
{
    public function internalError($msg): string
    {
        $this->setInternalServerErrorHeaders();

        //set status code 500
        //need extra log?
        return $this->renderError($msg);
    }

    public function notFound($msg): string
    {
        $this->setNotFoundHeaders();

        return $this->renderError($msg);
    }

    private function setNotFoundHeaders()
    {
        $this->setHeaders('404 not found', 404);
    }

    private function setInternalServerErrorHeaders()
    {
        $this->setHeaders('500 Internal Server Error', 500);
    }

    private function setHeaders(string $msg, int $httpStatusCode)
    {
        $server = $this->request->getServer();
        $serverProtocol = $server['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
        header($serverProtocol . ' ' . $msg, true, $httpStatusCode);
    }
}
