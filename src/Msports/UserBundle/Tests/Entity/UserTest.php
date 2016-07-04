<?php

namespace Msports\UserBundle\Tests\Entity;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $user = $this->getInstance();
        $this->assertNull($user->__toString());

        $user->setUsername('Jean-Jacques');
        $this->assertEquals('Jean-Jacques', $user->__toString());
    }

    public function testCreated()
    {
        $user = $this->getInstance();
        $this->assertNotNull($user->getCreated());
        $this->assertEquals(new \DateTime('now'), $user->getCreated());

        $user->setCreated(new \DateTime('yesterday'));
        $this->assertEquals(new \DateTime('yesterday'), $user->getCreated());
    }

    public function testActivated()
    {
        $user = $this->getInstance();
        $this->assertFalse($user->getActivated());

        $user->setActivated(true);
        $this->assertEquals(true, $user->getActivated());
    }

    public function getInstance()
    {
        return $this->getMockForAbstractClass('Msports\UserBundle\Entity\User');
    }
}
