<?php

namespace MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\postRepository")
 */
class Post
{
    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="MainBundle\Entity\Category")
     *
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="MainBundle\Entity\Tag")
     */
    private $tags;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @Assert\File(
     *     maxSize = "1024k"
     * )
     *
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 2000,
     *     minHeight = 200,
     *     maxHeight = 2000
     * )
     */
    private $imageFile;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 5, minMessage = "Title too short")
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modification_date", type="datetime", nullable=true)
     */
    private $modificationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=255,  nullable=true)
     */
    private $caption;

    /**
     * @Assert\Url()
     *
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255,  nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text",  nullable=true)
     */
    private $content;

    //@Assert\Choice({"text", "video", "picture", "link"})

    /**
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    public function __constructor($categories, $tags){
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    #region Accessors
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set iD
     *
     * @param integer $iD
     *
     * @return post
     */
    public function setID($iD)
    {
        $this->iD = $iD;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return post
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return post
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return post
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        if (is_null($this->user)) {
            $this->user = new User();
            $this->user->setPseudo("Anonymous");
        }
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    public function removeTag($tag)
    {

    }

// Used for ManyToMany
//    public function getCategories(){
//        return $this->categories;
//    }
//
//    public function addCategory($category){
//        $this->categories[] = $category;
//        return $this;
//    }
//
//    public function removeCategory($category) {
//
//    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * @param \DateTime $modificationDate
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    }
    #endregion
}

