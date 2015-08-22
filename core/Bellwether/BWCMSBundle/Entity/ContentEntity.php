<?php
namespace Bellwether\BWCMSBundle\Entity;


use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Bellwether\BWCMSBundle\Classes\Constants\ContentSortByType;
use Bellwether\BWCMSBundle\Classes\Constants\ContentScopeType;
use Bellwether\BWCMSBundle\Classes\Constants\ContentSortOrderType;
use Bellwether\BWCMSBundle\Classes\Constants\ContentPublishType;


/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Bellwether\BWCMSBundle\Entity\ContentRepository")
 * @ORM\Table(name="BWContent")
 */
class ContentEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expireDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishDate;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="guid", nullable=true, name="treeRoot")
     */
    private $treeRoot;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer", nullable=true, name="treeRight")
     */
    private $treeRight;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer", nullable=true, name="treeLevel")
     */
    private $treeLevel;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer", nullable=true, name="treeLeft")
     */
    private $treeLeft;

    /**
     * @ORM\OneToMany(targetEntity="Bellwether\BWCMSBundle\Entity\ContentEntity", mappedBy="treeParent")
     * @ORM\OrderBy({"treeLeft"="ASC"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Bellwether\BWCMSBundle\Entity\ContentMetaEntity", mappedBy="content")
     */
    private $meta;

    /**
     * @ORM\OneToMany(targetEntity="Bellwether\BWCMSBundle\Entity\ContentMediaEntity", mappedBy="content")
     */
    private $media;

    private $loadedMeta = null;

    /**
     * @ORM\OneToMany(targetEntity="Bellwether\BWCMSBundle\Entity\ContentRelationEntity", mappedBy="content")
     */
    private $relation;

    private $loadedRelation = null;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Bellwether\BWCMSBundle\Entity\ContentEntity", inversedBy="children")
     * @ORM\JoinColumn(name="treeParentId", referencedColumnName="id", onDelete="CASCADE")
     */
    private $treeParent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $type = 'Content';

    /**
     * @ORM\Column(type="string", length=100, nullable=false, name="schemaType")
     */
    private $schema = "Folder";

    /**
     * @ORM\Column(type="string", length=100, nullable=false, name="scope")
     */
    private $scope = ContentScopeType::CPublic;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mime;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $extension;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $sortBy = ContentSortByType::SortIndex;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $sortOrder = ContentSortOrderType::ASC;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $status = ContentPublishType::Published;

    /**
     * @ORM\ManyToOne(targetEntity="Bellwether\BWCMSBundle\Entity\UserEntity")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Bellwether\BWCMSBundle\Entity\UserEntity")
     * @ORM\JoinColumn(name="lastModifiedAuthorId", referencedColumnName="id", nullable=true)
     */
    private $lastModifiedAuthor;

    /**
     * @ORM\ManyToOne(targetEntity="Bellwether\BWCMSBundle\Entity\SiteEntity")
     * @ORM\JoinColumn(name="siteId", referencedColumnName="id", nullable=false)
     */
    private $site;


    public function __construct()
    {
        $this->meta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->media = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->scope = ContentScopeType::CPublic;
        $this->sortBy = ContentSortByType::Created;
        $this->sortOrder = ContentSortOrderType::DESC;
        $this->status = ContentPublishType::Draft;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * @param mixed $expireDate
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;
    }

    /**
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param mixed $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return mixed
     */
    public function getTreeRoot()
    {
        return $this->treeRoot;
    }

    /**
     * @param mixed $treeRoot
     */
    public function setTreeRoot($treeRoot)
    {
        $this->treeRoot = $treeRoot;
    }

    /**
     * @return mixed
     */
    public function getTreeRight()
    {
        return $this->treeRight;
    }

    /**
     * @param mixed $treeRight
     */
    public function setTreeRight($treeRight)
    {
        $this->treeRight = $treeRight;
    }

    /**
     * @return mixed
     */
    public function getTreeLevel()
    {
        return $this->treeLevel;
    }

    /**
     * @param mixed $treeLevel
     */
    public function setTreeLevel($treeLevel)
    {
        $this->treeLevel = $treeLevel;
    }

    /**
     * @return mixed
     */
    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    /**
     * @param mixed $treeLeft
     */
    public function setTreeLeft($treeLeft)
    {
        $this->treeLeft = $treeLeft;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return ContentEntity
     */
    public function getTreeParent()
    {
        return $this->treeParent;
    }

    /**
     * @param mixed $treeParent
     */
    public function setTreeParent($treeParent)
    {
        $this->treeParent = $treeParent;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSummary($actualSummary = false)
    {
        if($actualSummary){
            return $this->summary;
        }
        $summary = $this->summary;
        if (empty($summary)) {
            if (!empty($this->content)) {
                $html = new \Html2Text\Html2Text($this->content);
                $summary = $html->getText();
                $summary = preg_replace('/\s+/', ' ', $summary);
                $summary = trim($summary);
            }
        }
        return $summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param mixed $schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param mixed $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param mixed $sortBy
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }


    /**
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * @param mixed $modifiedDate
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getLastModifiedAuthor()
    {
        return $this->lastModifiedAuthor;
    }

    /**
     * @param mixed $lastModifiedAuthor
     */
    public function setLastModifiedAuthor($lastModifiedAuthor)
    {
        $this->lastModifiedAuthor = $lastModifiedAuthor;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @param ContentMetaEntity $meta
     * @return $this
     */
    public function addMeta(ContentMetaEntity $meta)
    {
        $this->meta->add($meta);
        return $this;
    }

    /**
     * @param ContentMetaEntity $meta
     * @return $this
     */
    public function removeMeta(ContentMetaEntity $meta)
    {
        $this->meta->removeElement($meta);
        return $this;
    }

    /**
     * @param ContentMediaEntity $meta
     * @return $this
     */
    public function addMedia(ContentMediaEntity $meta)
    {
        $this->meta->add($meta);
        return $this;
    }

    /**
     * @param ContentMediaEntity $meta
     * @return $this
     */
    public function removeMedia(ContentMediaEntity $meta)
    {
        $this->meta->removeElement($meta);
        return $this;
    }

    /**
     * @return null
     */
    public function getLoadedMeta()
    {
        return $this->loadedMeta;
    }

    /**
     * @param null $loadedMeta
     */
    public function setLoadedMeta($loadedMeta)
    {
        $this->loadedMeta = $loadedMeta;
    }

    /**
     * @param ContentRelationEntity $relation
     * @return $this
     */
    public function addRelation(ContentRelationEntity $relation)
    {
        $this->relation->add($relation);
        return $this;
    }

    /**
     * @param ContentRelationEntity $relation
     * @return $this
     */
    public function removeRelation(ContentRelationEntity $relation)
    {
        $this->relation->removeElement($relation);
        return $this;
    }

    /**
     * @return null
     */
    public function getLoadedRelation()
    {
        return $this->loadedRelation;
    }

    /**
     * @param null $loadedRelation
     */
    public function setLoadedRelation($loadedRelation)
    {
        $this->loadedRelation = $loadedRelation;
    }

}