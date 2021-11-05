<?php

namespace App\Controller\Saas\Cms;

use App\Repository\PageRepository;
use Universe\Asteroids\Asteroids;
use Universe\Starship\Controller;
use Universe\Telescope\RestApi;
use Universe\Telescope\View;


class PageController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @Route("list", methods={"GET"}, name="dashboard")
     */
    public function main(PageRepository $pageRepo){
        $pageList = $pageRepo->findBy(['project_id'=>27]);
        $response = [];
        foreach($pageList as $row){
            $response[] = [
                'id'=>$row->getId(),
                'name'=>$row->getName(),
                'metaTitle'=>$row->getMetaTitle(),
                'metaDescription'=>$row->getMetaDescription(),
                'slug'=>$row->getSlug(),
                'pageTemplate'=>$row->getPageTemplate(),
                'isPublished'=>$row->getIsPublished()
            ];
        }
        return $this->restApi($response);
    }

    /**
     * @Route("edit/{id}", methods={"GET"}, name="dashboard")
     */
    public function edit(PageRepository $pageRepo, int $id) : RestApi {
        try {
            $page = $pageRepo->find($id);
            if (!$page){
                throw new Asteroids('test');
            }
            $response[] = [
                'id'=>$page->getId(),
                'name'=>$page->getName(),
                'metaTitle'=>$page->getMetaTitle(),
                'metaDescription'=>$page->getMetaDescription(),
                'slug'=>$page->getSlug(),
                'pageTemplate'=>$page->getPageTemplate(),
                'isPublished'=>$page->getIsPublished()
            ];
            return $this->restApi($page);
        } catch(Asteroids $e){
            return $this->restApi(false);
        }
    }


}