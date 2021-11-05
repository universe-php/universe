<?php

namespace Universe\Addon\Page\Entity;
use Universe\Starship\Entity;

/**
 *
 * @ORM\Table(name="tbl_page")
 * @ORM\Entity(repositoryClass="Universe\Addon\Page\Repository\PageRepository")
 */
class Page
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="meta_title", type="string", length=255)
     */
    private $metaTitle;

    /**
     * @ORM\Column(name="meta_description", type="string", length=255)
     */
    private $metaDescription;

    /**
     * @ORM\Column(name="meta_keywords", type="string", length=255)
     */
    private $metaKeywords;

    /**
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(name="page_template", type="string", length=255)
     */
    private $pageTemplate;

    /**
     * @ORM\Column(name="page_classname", type="string", length=255)
     */
    private $pageClassName;

    /**
     * @return mixed
     */
    public function getPageClassName()
    {
        return $this->pageClassName;
    }

    /**
     * @param mixed $pageClassName
     */
    public function setPageClassName($pageClassName): void
    {
        $this->pageClassName = $pageClassName;
    }

    /**
     * @ORM\Column(name="is_published", type="string", length=255)
     */
    private $isPublished;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title): void
    {
        $this->metaTitle = $meta_title;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description): void
    {
        $this->metaDescription = $meta_description;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param mixed $meta_keywords
     */
    public function setMetaKeywords($meta_keywords): void
    {
        $this->metaKeywords = $meta_keywords;
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
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getPageTemplate()
    {
        return $this->pageTemplate;
    }

    /**
     * @param mixed $page_template
     */
    public function setPageTemplate($page_template): void
    {
        $this->pageTemplate = $page_template;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $is_published
     */
    public function setIsPublished($is_published): void
    {
        $this->isPublished = $is_published;
    }

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $is_deleted
     */
    public function setIsDeleted($is_deleted): void
    {
        $this->isDeleted = $is_deleted;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updatedAt = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at): void
    {
        $this->deletedAt = $deleted_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->createdAt = $created_at;
    }



}
