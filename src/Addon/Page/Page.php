<?php

namespace Universe\Addon\Page;

use Universe\Addon\Page\Repository\PageBlockRepository;
use Universe\Addon\Page\Repository\PageBlockTwigRepository;
use Universe\Addon\Page\Repository\PageRepository;
use Universe\Asteroids\Asteroids;
use Universe\Starship\Controller;


class Page extends Controller
{

    private $pageRepo;
    private $pageBlockRepo;
    private $pageBlockTwigRepo;

    public function __construct()
    {
        parent::__construct();
        $this->pageRepo = new PageRepository();
        $this->pageBlockRepo = new PageBlockRepository();
        $this->pageBlockTwigRepo = new PageBlockTwigRepository();
    }


    private function blocks(int $pageId){
        $pageBlocks = $this->pageBlockRepo->findBy(['page_id' => $pageId]);
        $blocks = [];
        foreach($pageBlocks as $block){
            $content = $block->getContent();
            $pageTwig = $this->pageBlockTwigRepo->find($block->getId());
            var_dump($pageTwig);
            $blocks[] = [
                'blockTemplate'=>$content->blockTemplate,
                'blockItems'=>$content->itemTemplate,
                'twig'=>[
                    'blockName'=>$pageTwig->getBlockName(),
                    'blockFile'=>$pageTwig->getBlockFile(),
                    'blockCode'=>$pageTwig->getBlockCode(),
                    'sectionClassName'=>$pageTwig->getSectionClassname(),
                    'innerWrapperClassName'=>$pageTwig->getInnerWrapperClassname()
                ]
            ];
        }
        return $blocks;
    }

    private function pageData($page){
        return [
            'id' => $page->getId(),
            'name' => $page->getName(),
            'seo'=> [
                'title' => $page->getMetaTitle(),
                'metaDescription' => $page->getMetaDescription()
            ],
            'slug' => $page->getSlug(),
            'pageTemplate' => $page->getPageTemplate(),
            'pageClassName' => $page->getPageClassName(),
            'isPublished' => $page->getIsPublished(),
            'blocks' => $this->blocks($page->getId())
        ];
    }

    public function getById(int $id)
    {
        $page = $this->pageRepo->find($id);
        return $this->pageData($page);
    }

    public function getBySlug(string $slug)
    {
        $page = $this->pageRepo->findOneBy(['slug' => $slug]);
        return $this->pageData($page);
    }

    public function list()
    {
        $pageRepo = new PageRepository();
        $pageList = $pageRepo->findBy(['project_id' => 27]);
        $response = [];
        foreach ($pageList as $row) {
            $response[] = [
                'id' => $row->getId(),
                'name' => $row->getName(),
                'metaTitle' => $row->getMetaTitle(),
                'metaDescription' => $row->getMetaDescription(),
                'slug' => $row->getSlug(),
                'pageTemplate' => $row->getPageTemplate(),
                'isPublished' => $row->getIsPublished()
            ];
        }
        return $response;
    }

    public function edit(PageRepository $pageRepo, int $id)
    {
        try {
            $page = $pageRepo->find($id);
            if (!$page) {
                throw new Asteroids('test');
            }
            $response[] = [
                'id' => $page->getId(),
                'name' => $page->getName(),
                'metaTitle' => $page->getMetaTitle(),
                'metaDescription' => $page->getMetaDescription(),
                'slug' => $page->getSlug(),
                'pageTemplate' => $page->getPageTemplate(),
                'isPublished' => $page->getIsPublished()
            ];
            return $page;
        } catch (Asteroids $e) {
            return false;
        }
    }

}