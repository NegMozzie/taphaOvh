<?php
/**
 * Created by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 7.7.15.
 * Time: 15.52
 */

namespace BlogBundle\Handler;


use Doctrine\Bundle\DoctrineBundle\Registry;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\ArticleMeta;
use BlogBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

class ArticleHandler
{
    protected $doctrine;
    protected $articleMetaClass;

    function __construct(Registry $doctrine, $articleMetaClass)
    {
        $this->doctrine = $doctrine;
        $this->articleMetaClass = $articleMetaClass;
    }

    /**
     * Will check if article is writing-locked to against blog user.
     * If NOT - it generate/update lock and return false
     * If YES - returns userId of the lock holder
     *
     * @param BlogUserEntity\ $user
     * @param ArticleEntity\ $article
     * @return mixed
     */
    public function isLocked(User $user, Article $article)
    {
        $metaLock = $this->findWritingLockMeta($article);

        if(!$metaLock)
        {
            //no lock detected
            $metaClass = $this->articleMetaClass;
            $metaLock = new $metaClass();
            $metaLock->setArticle($article);

            $this->applyLock($metaLock, $user);

            return false;
        }
        else
        {
            $arrayData = explode(':', $metaLock->getValue());
            $datetimeLock = $arrayData[0];
            $userLock = $arrayData[1];

            if($user->getId() == $userLock)
            {
                //user is holding the lock
                $this->applyLock($metaLock, $user);

                return false;
            }
            else
            {
                if(strtotime(date("Y-m-d H:i:s")) - $datetimeLock >= 30 )
                {
                    //lock expired
                    $this->applyLock($metaLock, $user);

                    return false;
                }
                else
                {
                    //lock is active - edit should be forbidden
                    return $userLock;
                }
            }
        }
    }

    /**
     * Finds last writing lock
     *
     * @param ArticleEntity\ $article
     * @return mixed
     */
    private function findWritingLockMeta(Article $article)
    {
        $metaLock = null;

        //search writing_lock data
        foreach($article->getMetaData() as $meta)
        {
            if($meta->getKey() == 'writing_locked')
            {
                $metaLock = $meta;

                break;
            }
        }

        return $metaLock;
    }

    private function applyLock(ArticleMeta &$meta, $user)
    {
        $datetimeLocked = strtotime(date("Y-m-d H:i:s"));
        $meta
            ->setKey('writing_locked')
            ->setValue($datetimeLocked . ':' . $user->getId());

        $this->save($meta);
    }

    private function save($meta)
    {
        $this->doctrine->getManager()->persist($meta);
        $this->doctrine->getManager()->flush();
    }

    public function takeoverLock(User $user, Article $article)
    {
        $metaLock = $this->findWritingLockMeta($article);
        $this->applyLock($metaLock, $user);

        $this->save($metaLock);
    }

}