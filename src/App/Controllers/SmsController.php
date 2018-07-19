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

    public function __construct(Request $request)
    {
        $this->setAllowGet(false);
        $this->setAllowPost(true);

        parent::__construct($request);
    }

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
        return $this->render('Sending Sms');
    }
}
