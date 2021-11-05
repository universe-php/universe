<?php

namespace Universe\Addon\Page\Repository;

use Universe\Addon\Page\Entity\PageBlockTwig;
use Universe\Starship\Repository;

/**
 * Class PageRepository
 * @package Universe\Addon\Page\Repository
 *
 * @method PageBlockTwig|null find($id)
 * @method PageBlockTwig|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageBlockTwig[]    findAll()
 * @method PageBlockTwig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class PageBlockTwigRepository extends Repository
{

    public function __construct()
    {
        parent::__construct(PageBlockTwig::class);
    }
}
