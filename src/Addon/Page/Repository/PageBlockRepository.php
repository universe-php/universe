<?php

namespace Universe\Addon\Page\Repository;

use Universe\Addon\Page\Entity\PageBlock;
use Universe\Starship\Repository;

/**
 * Class PageRepository
 * @package Universe\Addon\Page\Repository
 *
 * @method PageBlock|null find($id)
 * @method PageBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageBlock[]    findAll()
 * @method PageBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class PageBlockRepository extends Repository
{

    public function __construct()
    {
        parent::__construct(PageBlock::class);
    }
}
