<?php
declare(strict_types=1);

namespace App\Event;

use App\Entity\UserToken;
use App\Exception\ValidateEntityUnsuccessfulException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use \DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RefreshTokenEventListener
 */
class RefreshTokenEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(Security $security, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = $validator;
    }

    /**
     * @throws ValidateEntityUnsuccessfulException
     */
    public function onKernelController(): void
    {
        $user = $this->security->getUser();

        if ($user === null) {
            return;
        }

        $userToken = $user->getUserTokenReferences();

        $date = new DateTime('now');
        $date = $date->getTimestamp();
        $save = false;

        if ($userToken->getRefreshMobileToken()->getTimestamp() <= $date) {
            $userToken->setRefreshMobileToken(new DateTime('+10 day'));
            $userToken->setPrivateMobileToken((string) Uuid::uuid4());

            $save = true;
        }

        if ($userToken->getRefreshWebToken()->getTimestamp() <= $date) {
            $userToken->setRefreshWebToken(new DateTime('+1 day'));
            $userToken->setPrivateWebToken((string) Uuid::uuid4());

            $save = true;
        }

        if ($userToken->getRefreshPublicToken()->getTimestamp() <= $date) {
            $userToken->setRefreshPublicToken(new DateTime('+30 day'));
            $userToken->setPublicToken((string) Uuid::uuid4());

            $save = true;
        }


        if ($save) {
            $this->saveUserToken($userToken);
        }
    }

    /**
     * @param UserToken $userToken
     * @throws ValidateEntityUnsuccessfulException
     */
    private function saveUserToken(UserToken $userToken) :void
    {
        if (\count($this->validator->validate($userToken)) > 0) {
            throw new ValidateEntityUnsuccessfulException('Entity UserToken is failed in: ' . \get_class($this));
        }
        $this->entityManager->persist($userToken);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}

