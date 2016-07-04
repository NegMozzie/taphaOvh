<?php        
//src/Acme/DemoBundle/Entity/ArticleMeta.php

namespace Msports\BlogBundle\Entity; 

use ED\BlogBundle\Interfaces\Model\ArticleMetaInterface;
use ED\BlogBundle\Model\Entity\ArticleMeta as BaseArticleMeta;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="article_meta")
 * @ORM\Entity()
 */
class ArticleMeta extends BaseArticleMeta implements ArticleMetaInterface
{
}