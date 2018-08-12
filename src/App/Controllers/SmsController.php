<?php
namespace Akmb\App\Controllers;

use Akmb\App\Entities\Message;
use Akmb\App\Message\MessageQueue;
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
        return $this->validateKeysPresence($this->requiredParams, $request->getPost(), false)
            && $this->validatePattern('/^[1-9]{1}[0-9]{3,14}$/', $request->getPost()['destination'], 'Invalid MSISDN');

    }

    /**
     * @param Request $request
     * @return string
     */
    public function send(Request $request): string
    {
        try {
            /** @var Redis $redis */
            $redis = $this->serviceContainer->getService(Redis::class);

            $postData = $request->getPost();

            $message = new Message();
            $message->setDestination($postData['destination']);
            $message->setMessage($postData['message']);

            $messageQueue = $this->getMessageQueue($redis);

            if ($messageQueue->isDuplicated($message)) {
                return $this->renderError('Duplicated message.');
            }

            $messageQueue->queueMessage($message);

            return $this->render('Message sent.');
        } catch (\Exception $e) {
            return $this->renderError(sprintf(
                'Unable to send SMS. Error: [%s]',
                $e->getMessage()
            ));
        }
    }

    public function getMessageQueue(Redis $redis): MessageQueue
    {
        return new MessageQueue($redis);
    }
}
