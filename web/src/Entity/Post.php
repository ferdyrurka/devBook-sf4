<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post
 * @package App\Entity
 * @ORM\Table(name="post")
 * @ORM\Entity()
 */
class Post
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $postId;

    /**
     * @var string
     * @ORM\Column(type="string", length=16000)
     * @Assert\Regex(
     *     pattern="/^([A-ZĄĆĘŁŃÓŚŹŻ|a-ząćęłnóśźż|0-9| |,|.|-]){1,255}$/"
     * )
     */
    private $content;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", length=20)
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", length=20)
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userReferences;

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return User
     */
    public function getUserReferences(): User
    {
        return $this->userReferences;
    }

    /**
     * @param User $userReferences
     */
    public function setUserReferences(User $userReferences): void
    {
        $this->userReferences = $userReferences;
    }
}
