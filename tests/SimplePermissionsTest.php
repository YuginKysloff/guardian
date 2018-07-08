<?php

namespace Rennokki\Guardian\Test;

class SimplePermissionsTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(\Rennokki\Guardian\Test\Models\User::class)->create();
    }

    public function testOnEmpty()
    {
        $this->assertEquals($this->user->permissions()->count(), 0);
        $this->assertFalse($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->getPermission('accessTheLab'), null);

        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);

        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));
    }

    public function testAllowanceAndDisallowance()
    {
        $this->user->allow('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('accessTheLab'));
        $this->assertFalse($this->user->cannot('accessTheLab'));
        $this->assertFalse($this->user->cant('accessTheLab'));

        $this->user->disallow('accessTheLab', null, null);

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->allow('accessTheLab');
        $this->user->disallow('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->disallow('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));
    }

    public function testProhibition()
    {
        $this->user->allow('accessTheLab');

        $this->user->prohibit('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->unprohibit('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('accessTheLab'));
        $this->assertFalse($this->user->cannot('accessTheLab'));
        $this->assertFalse($this->user->cant('accessTheLab'));

        $this->user->prohibit('accessTheLab');
        $this->user->allow('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('accessTheLab'));
        $this->assertFalse($this->user->cannot('accessTheLab'));
        $this->assertFalse($this->user->cant('accessTheLab'));
    }

    public function disallowWithoutPermissionExistent()
    {
        $this->assertEquals($this->user->permissions()->count(), 0);
        $this->assertFalse($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->disallow('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->allow('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('accessTheLab'));
        $this->assertFalse($this->user->cannot('accessTheLab'));
        $this->assertFalse($this->user->cant('accessTheLab'));
    }

    public function prohibitWithoutPermissionExistent()
    {
        $this->assertEquals($this->user->permissions()->count(), 0);
        $this->assertFalse($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->prohibit('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 0);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 1);
        $this->assertFalse($this->user->can('accessTheLab'));
        $this->assertTrue($this->user->cannot('accessTheLab'));
        $this->assertTrue($this->user->cant('accessTheLab'));

        $this->user->unprohibit('accessTheLab');

        $this->assertEquals($this->user->permissions()->count(), 1);
        $this->assertTrue($this->user->hasPermission('accessTheLab'));
        $this->assertEquals($this->user->allowedPermissions()->count(), 1);
        $this->assertEquals($this->user->prohibitedPermissions()->count(), 0);
        $this->assertTrue($this->user->can('accessTheLab'));
        $this->assertFalse($this->user->cannot('accessTheLab'));
        $this->assertFalse($this->user->cant('accessTheLab'));
    }
}
