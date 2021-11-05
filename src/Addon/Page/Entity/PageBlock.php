<?php

namespace Universe\Addon\Page\Entity;
use Universe\Starship\Entity;

/**
 *
 * @ORM\Table(name="tbl_page_content")
 * @ORM\Entity(repositoryClass="Universe\Addon\Page\Repository\PageBlockRepository")
 */
class PageBlock
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="project_id", type="integer", length=255)
     */
    private $projectId;

    /**
     * @ORM\Column(name="page_id", type="integer", length=255)
     */
    private $pageId;

    /**
     * @ORM\Column(name="block_id", type="integer", length=255)
     */
    private $blockId;

    /**
     * @ORM\Column(name="position", type="integer", length=11)
     */
    private $position;

    /**
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(name="is_deleted", type="string", length=255)
     */
    private $isDeleted;

    /**
     * @ORM\Column(name="updated_at", type="string", length=9)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="string", length=9)
     */
    private $deletedAt;

    /**
     * @ORM\Column(name="created_at", type="string", length=9)
     */
    private $createdAt;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param mixed $project_id
     */
    public function setProjectId($project_id): void
    {
        $this->projectId = $project_id;
    }

    /**
     * @return mixed
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * @param mixed $pageId
     */
    public function setPageId($pageId): void
    {
        $this->pageId = $pageId;
    }

    /**
     * @return mixed
     */
    public function getBlockId()
    {
        return $this->blockId;
    }

    /**
     * @param mixed $blockId
     */
    public function setBlockId($blockId): void
    {
        $this->blockId = $blockId;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return json_decode($this->content);
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     */
    public function setIsDeleted($isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }


}
