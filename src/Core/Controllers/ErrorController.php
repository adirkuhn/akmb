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

    public function badRequest($msg): string
    {
        $this->setBadRequestHeaders();

        return $this->renderError($msg);
    }

    public function methodIsNotAllowed($msg): string
    {
        $this->setHeaders('method is not allowed', 405);
        return $this->renderError($msg);
    }

    private function setBadRequestHeaders()
    {
        $this->setHeaders('400 Bad request', 400);
    }

    private function setNotFoundHeaders()
    {
        $this->setHeaders('404 not found', 404);
    }

    private function setInternalServerErrorHeaders()
    {
        $this->setHeaders('500 Internal Server Error', 500);
    }
}
