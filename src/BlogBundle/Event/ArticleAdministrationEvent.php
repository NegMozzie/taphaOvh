<?php
/**
 * Created by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 3.6.15.
 * Time: 13.30
 */

namespace BlogBundle\Event;


use BlogBundle\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class ArticleAdministrationEvent extends Event
{
    protected $article;

    function __construct(Article $article=null)
    {
        $this->article = $article;
    }

    public function getArticle()
    {
        return $this->article;
    }
}