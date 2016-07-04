<?php
//src/Acme/DemoBundle/Entity/Settings.php

namespace Msports\BlogBundle\Entity; 

use ED\BlogBundle\Interfaces\Model\BlogSettingsInterface;
use ED\BlogBundle\Model\Entity\BlogSettings as BaseSettings;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="ED\BlogBundle\Model\Repository\BlogSettingsRepository")
 */
class Config extends BaseSettings implements BlogSettingsInterface
{
}