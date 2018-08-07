<?php
namespace Akmb\App\Controllers;

use Akmb\App\Constants\Queue;
use Akmb\Core\Controllers\DefaultController;
use Akmb\Core\Extra\Validator;
use Akmb\Core\Request;
use Akmb\Core\ServiceContainer\ServiceContainer;
use Akmb\Core\Services\Redis\Redis;

class SmsController extends DefaultController
{
    use Validator;

    private $requiredParams = [
        'destination',
        'message'
    ];

    public function __construct(Request $request, ServiceContainer $serviceContainer)
    {
        $this->setAllowGet(false);
        $this->setAllowPost(true);

        parent::__construct($request, $serviceContainer);
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

    /**
     * @param Request $request
     * @return string
     * @throws \Akmb\Core\ServiceContainer\Exceptions\ServiceNotFoundException
     */
    public function send(Request $request): string
    {
        /** @var Redis $redis */
        $redis = $this->serviceContainer->getService(Redis::class);

        try {
            $postData = $request->getPost();

            $message = [
                'destination' => $postData['destination'],
                'message' => $postData['message']
            ];

            $serializeMessage = serialize($message);
            $setMember = md5($serializeMessage);

            if ($redis->getSetsMember(Queue::SmsSetMessageName, $setMember)) {
                return $this->renderError('Duplicated message.');
            }

            $redis->addSetsMember(Queue::SmsSetMessageName, $setMember);
            $redis->saveDataToQueue(Queue::SmsListMessageName, $serializeMessage);

            return $this->render('Message sent.');
        } catch (\Exception $e) {
            return $this->renderError(sprintf(
                'Unable to send SMS. Error: [%s]',
                $e->getMessage()
            ));
        }
    }
}
