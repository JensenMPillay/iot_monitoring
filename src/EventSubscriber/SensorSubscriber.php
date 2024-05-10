<?php

namespace App\EventSubscriber;

use App\Entity\Log;
use App\Entity\Sensor;
use App\Enum\EventType;
use App\Event\SensorEvent;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Config\Framework\Notifier\AdminRecipientConfig;

class SensorSubscriber implements EventSubscriberInterface
{
    /**
     * Constructs `Sensor` subscriber
     *
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @param NotifierInterface $notifier
     */
    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $entityManager, private readonly NotifierInterface $notifier)
    {
    }

    /**
     * Retrieves subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EventType::STATUS_CHANGED->value => 'onSensorStatusEvent',
            EventType::VALUE_CHANGED->value => 'onSensorValueEvent'
        ];
    }

    /**
     * Simulates a changed `status` event on `Sensor`.
     *
     * @param SensorEvent $event
     * @return void
     */
    public function onSensorStatusEvent(SensorEvent $event): void
    {
        if ($event->getSensor()->isStatus()) {
            $sensor = $event
                ->getSensor()
                ->setStatus(false)
                ->setValue("0")
                ->setUptime(null);

            $this->sendNotification($event);
        } else {
            $sensor = $event
                ->getSensor()
                ->setStatus(true)
                ->setRandomValue()
                ->setUptime(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        }

        $this->save($sensor, EventType::STATUS_CHANGED);
    }


    /**
     * Simulates a changed `value` event on `Sensor`.
     *
     * @param SensorEvent $event
     * @return void
     */
    public function onSensorValueEvent(SensorEvent $event): void
    {
        if (!$event->getSensor()->isStatus()) return;

        $sensor = $event
            ->getSensor()
            ->setDataSentCount($event->getSensor()->getDataSentCount() + 1)
            ->setRandomValueInRange()
            ->setUpdatedAt(new \DateTimeImmutable());

        $this->save($sensor, EventType::VALUE_CHANGED);
    }

    /**
     * Snapshots and persists to database.
     *
     * @param Sensor $sensor
     * @return void
     */
    private function save(Sensor $sensor, EventType $eventType): void
    {
        $log = (new Log())->snapShot($sensor, $eventType);

        $this->logger->notice('⏱️ - ' . $log->getEvent() . ' - ' . $log->getType()->value);

        $this->entityManager->persist($sensor);
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    /**
     * Sends a email notification when a `Sensor` is down.
     *
     * @param SensorEvent $event
     * @return void
     */
    private function sendNotification(SensorEvent $event): void
    {
        $notification = (new Notification())
            ->emoji('❌')
            ->subject("SENSOR DOWN !")
            ->content("The Sensor \"" . $event->getSensor()->getName() . "\" is DOWN. " . strval((new \DateTime())->format(DateTimeInterface::COOKIE)))
            ->importance(Notification::IMPORTANCE_URGENT);

        $this->logger->warning($notification->getEmoji() . ' - ' . $event->getSensor()->getName() . ' - ' . $notification->getSubject());

        $this->notifier->send($notification, new Recipient("admin@example.com"));
    }
}
