<?php
namespace AirSim\Bundle\SocialNetworkBundle\WebSocketServices;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use AirSim\Bundle\SocialNetworkBundle\Modules\UserModule;

class Pusher implements WampServerInterface
{
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {

        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }

        echo sprintf("New Connection: %s; Topic: %s".PHP_EOL, $conn->remoteAddress, $topic);
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry)
    {

        $entryData = json_decode($entry, true);
        $entryData = json_decode($entryData, true);

        // send data to all users located on this page or send notification to specific user
        if(array_key_exists($entryData['eventData']['page'], $this->subscribedTopics))
        {
            $topic = $this->subscribedTopics[$entryData['eventData']['page']];
            $topic->broadcast($entryData);
        }
        if(array_key_exists('id'.$entryData['receiverData']['receiverId'], $this->subscribedTopics))
        {
            $topic = $this->subscribedTopics['id'.$entryData['receiverData']['receiverId']];
            $topic->broadcast($entryData);
        }
        else return;

    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}