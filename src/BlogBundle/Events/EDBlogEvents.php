<?php
/**
 * Created by Eton Digital.
 * User: Vladimir Mladenovic (vladimir.mladenovic@etondigital.com)
 * Date: 3.6.15.
 * Time: 13.28
 */

namespace BlogBundle\Events;


use Symfony\Component\EventDispatcher\Event;

class EDBlogEvents extends Event
{
    const ED_BLOG_ARTICLE_PREUPDATE_INIT = "blog.article.pre_update.init";
    const ED_BLOG_ARTICLE_POST_UPDATE = "blog.article.post_update";
    const ED_BLOG_ARTICLE_REMOVED = "blog.article.removed";
    const ED_BLOG_ARTICLE_CREATED = "blog.article.created";
    const ED_BLOG_COMMENT_CREATED = "blog.comment.created";
    const ED_BLOG_MEDIA_LIBRARY_MEDIA_UPLOADED = "blog.media_library.media.uploaded";
}