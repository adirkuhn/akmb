<?php
namespace Akmb\App\Controllers;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Extra\Validator;
use Akmb\Core\Request;

class SmsController extends DefaultController
{
    use Validator;

    private $requiredParams = [
        'destination',
        'message'
    ];

    /**
     * Check for required params
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        return $this->validateKeysPresence($this->requiredParams, $request->getPost(), false);
    }

    public function send(Request $request)
    {
        $server = $request->getServer();

        if ($server['REQUEST_METHOD'] === $request::POST) {

            return $this->render('Sending Sms');
        }

        return $this->renderError(sprintf(
            '[%s] method is not supported, please use [%s]',
            $server['REQUEST_METHOD'],
            $request::POST
        ));
    }
}
