MsportsUserBundle
===========

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/803e613c-6e74-4774-9bec-e529adbdb27e/mini.png)](https://insight.sensiolabs.com/projects/803e613c-6e74-4774-9bec-e529adbdb27e)
[![Build Status](https://travis-ci.org/Msports/MsportsUserBundle.svg?branch=master)](https://travis-ci.org/Msports/MsportsUserBundle)
[![StyleCI](https://styleci.io/repos/50055973/shield)](https://styleci.io/repos/50055973)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Msports/MsportsUserBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Msports/MsportsUserBundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/Msports/userbundle/v/stable)](https://packagist.org/packages/Msports/userbundle)


The MsportsUserBundle is an integration of FOSUserBundle + Sonata admin for Msports
projects.

It comes bundled with 2 main entity :
- An abstract BaseUser which should be used to create your main user entity in the project
- An admin entity extending the BaseUser to create administrator
- Fixtures for the admin entity

## Installation


* Install the package
```
composer require 'Msports/userbundle:~2.0'
```


* Update AppKernel.php
```

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Msports\UserBundle\MsportsUserBundle(),
            );

            // ...
        }

        // ...
    }
```

* Update DB Schema

```
php app/console doctrine:schema:update
```

* Create a User entity in your AppBundle extending the BaseUser

```
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msports\UserBundle\Entity\User as BaseUser;


/**
 * @ORM\Entity
 * @ORM\Table(name="account_user")
 */
class User extends BaseUser
{

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

}
```


* Add the routing

```
admin:
    resource: '@MsportsUserBundle/Resources/config/routing.yml'
```

* Change security.yml (optionnal)

If you have more than an admin firewall, you should copy the security.yml of the bundle and paste it in your main security.yml to edit it.



* Customize login page

For every firewall you have to specify a template for the login page. The admin login template is always provided.
Otherwise, it should be defined using the msport_user.firewall_templates parameters :

```
msport_user:
    firewall_templates:
        admin:
            login_template: 'MsportsUserBundle:admin:pages/back_login.html.twig'

```

You can also simply change the color and the background image of the admin page by overriding these parameters

```
msport_user:
    default_login_background_image: '../../bundles/Msportsuser/img/background.jpg'
    default_login_background_color: '#ff656c'
```
