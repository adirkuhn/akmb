<?php
namespace Akmb\App\Controllers;

use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Request;

class SmsController extends DefaultController
{
    private $acceptMethod = 'POST';

    private $requiredParams = [
        'destination',
        'message'
    ];

    /**
     * Check for required params
     * 
     * @param array $params
     * @return array
     */
    private function validate(array $params): array
    {
        if (array_intersect(array_keys($params), $this->requiredParams) !== count($this->requiredParams)) {
            return [
                false,
                sprintf(
                    'Missing required params. Required [%s] - Received [%s]',
                    http_build_query($this->requiredParams),
                    http_build_query($params)
                )
            ];
        }

        return [true, ''];
    }

    public function send(Request $request)
    {
        $server = $request->getServer();

        if ($server['REQUEST_METHOD'] === $request::POST) {
            $a = $this->validate($request->getPost());
            syslog(1, var_export($a, 1));
            return $this->render('Sending Sms');
        }

        return $this->renderError(sprintf(
            '[%s] method is not supported, please use [%s]',
            $server['REQUEST_METHOD'],
            $request::POST
        ));
    }
}
