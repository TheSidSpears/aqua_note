<?php


namespace App\Service;


class MessageManager
{
    /**
     * @var array
     */
    private $encouragingMessages = array();
    /**
     * @var array
     */
    private $discouragingMessages = array();

    /**
     * MessageManager constructor.
     */
    public function __construct(array $encouragingMessages, array $discouragingMessages)
    {
        $this->encouragingMessages = $encouragingMessages;
        $this->discouragingMessages = $discouragingMessages;
    }

    /**
     * @return array
     */
    public function getEncouragingMessage()
    {
        return $this->encouragingMessages[array_rand($this->encouragingMessages)];
    }

    /**
     * @return array
     */
    public function getDiscouragingMessage()
    {
        return $this->discouragingMessages[array_rand($this->discouragingMessages)];
    }
}