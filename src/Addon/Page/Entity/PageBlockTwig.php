<?php

namespace Universe\Addon\Page\Entity;
use Universe\Starship\Entity;
/**
 *
 * @ORM\Table(name="tbl_page_block")
 * @ORM\Entity(repositoryClass="Universe\Addon\Page\Repository\PageBlockTwigRepository")
 */
class PageBlockTwig
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="block_name", type="string", length=255)
     */
    private $blockName;

    /**
     * @ORM\Column(name="block_file", type="string", length=255)
     */
    private $blockFile;

    /**
     * @ORM\Column(name="block_code", type="string", length=255)
     */
    private $blockCode;

    /**
     * @ORM\Column(name="block_template", type="string", length=255)
     */
    private $blockTemplate;

    /**
     * @ORM\Column(name="section_classname", type="string", length=255)
     */
    private $sectionClassname;

    /**
     * @ORM\Column(name="inner_wrapper_classname", type="string", length=255)
     */
    private $innerWrapperClassname;


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
    public function getBlockName()
    {
        return $this->blockName;
    }

    /**
     * @param mixed $blockName
     */
    public function setBlockName($blockName): void
    {
        $this->blockName = $blockName;
    }

    /**
     * @return mixed
     */
    public function getBlockFile()
    {
        return $this->blockFile;
    }

    /**
     * @param mixed $blockFile
     */
    public function setBlockFile($blockFile): void
    {
        $this->blockFile = $blockFile;
    }

    /**
     * @return mixed
     */
    public function getBlockCode()
    {
        return $this->blockCode;
    }

    /**
     * @param mixed $blockCode
     */
    public function setBlockCode($blockCode): void
    {
        $this->blockCode = $blockCode;
    }

    /**
     * @return mixed
     */
    public function getBlockTemplate()
    {
        return $this->blockTemplate;
    }

    /**
     * @param mixed $blockTemplate
     */
    public function setBlockTemplate($blockTemplate): void
    {
        $this->blockTemplate = $blockTemplate;
    }

    /**
     * @return mixed
     */
    public function getSectionClassname()
    {
        return $this->sectionClassname;
    }

    /**
     * @param mixed $sectionClassname
     */
    public function setSectionClassname($sectionClassname): void
    {
        $this->sectionClassname = $sectionClassname;
    }

    /**
     * @return mixed
     */
    public function getInnerWrapperClassname()
    {
        return $this->innerWrapperClassname;
    }

    /**
     * @param mixed $innerWrapperClassname
     */
    public function setInnerWrapperClassname($innerWrapperClassname): void
    {
        $this->innerWrapperClassname = $innerWrapperClassname;
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
