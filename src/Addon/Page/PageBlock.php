<?php

namespace Universe\Addon\Page;

use Universe\Addon\Page\Repository\PageBlockRepository;
use Universe\Addon\Page\Repository\PageBlockTwigRepository;
use Universe\Addon\Page\Repository\PageRepository;
use Universe\Asteroids\Asteroids;
use Universe\Starship\Controller;


class PageBlock extends Controller
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

    public function list()
    {
        $blockRepo = new PageBlockTwigRepository();
        return $blockRepo->all();
    }

}