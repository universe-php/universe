<?php

namespace App\Repository;

use App\Entity\Page;
use Universe\Starship\Repository;

/**
 * Class PageRepository
 * @package App\Repository
 *
 * @method Page|null find($id)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class PageRepository extends Repository
{

    public function __construct()
    {
        parent::__construct(Page::class);
    }
}
