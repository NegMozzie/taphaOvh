<?php
//src/Acme/DemoBundle/Entity/Term.php

namespace Msports\BlogBundle\Entity; 

use ED\BlogBundle\Interfaces\Model\BlogTermInterface;
use ED\BlogBundle\Model\Entity\Term as BaseTerm;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="term")
 * @ORM\Entity()
 * @UniqueEntity("slug")
 */
class Term extends BaseTerm implements BlogTermInterface
{
}