<?php

namespace Rennokki\Guardian\Test;

use Rennokki\Guardian\Test\Models\Post;

class ModelGlobalTest extends TestCase
{
    protected $user;
    protected $targetInstance = Post::class;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(\Rennokki\Guardian\Test\Models\User::class)->create();
    }

    public function testOnEmpty()
    {
        $this->assertEquals($this->user->permissions()->count(), 0);
        $this->assertFalse($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->getPermission('edit', $this->targetInstance), null);

        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);

        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
    }

    public function testAllowanceAndDisallowance()
    {
        $this->user->allow('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('edit', $this->targetInstance));
        $this->assertFalse($this->user->cannot('edit', $this->targetInstance));
        $this->assertFalse($this->user->cant('edit', $this->targetInstance));

        $this->user->disallow('edit', $this->targetInstance, null);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->allow('edit', $this->targetInstance);
        $this->user->disallow('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->disallow('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));
    }

    public function testProhibition()
    {
        $this->user->allow('edit', $this->targetInstance);

        $this->user->prohibit('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->unprohibit('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('edit', $this->targetInstance));
        $this->assertFalse($this->user->cannot('edit', $this->targetInstance));
        $this->assertFalse($this->user->cant('edit', $this->targetInstance));

        $this->user->prohibit('edit', $this->targetInstance);
        $this->user->allow('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('edit', $this->targetInstance));
        $this->assertFalse($this->user->cannot('edit', $this->targetInstance));
        $this->assertFalse($this->user->cant('edit', $this->targetInstance));
    }

    public function disallowWithoutPermissionExistent()
    {
        $this->assertEquals($this->user->permissions()->count(), 0);
        $this->assertFalse($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->disallow('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->allow('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('edit', $this->targetInstance));
        $this->assertFalse($this->user->cannot('edit', $this->targetInstance));
        $this->assertFalse($this->user->cant('edit', $this->targetInstance));
    }

    public function prohibitWithoutPermissionExistent()
    {
        $this->assertEquals($this->user->permissions()->count(), 0);
        $this->assertFalse($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->prohibit('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('edit', $this->targetInstance));
        $this->assertTrue($this->user->cannot('edit', $this->targetInstance));
        $this->assertTrue($this->user->cant('edit', $this->targetInstance));

        $this->user->unprohibit('edit', $this->targetInstance);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('edit', $this->targetInstance));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('edit', $this->targetInstance));
        $this->assertFalse($this->user->cannot('edit', $this->targetInstance));
        $this->assertFalse($this->user->cant('edit', $this->targetInstance));
    }
}
