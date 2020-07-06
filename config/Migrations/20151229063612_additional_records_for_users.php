<?php
use Migrations\AbstractMigration;

class AdditionalRecordsForUsers extends AbstractMigration
{
    public function up()
    {
        $usersTable = \Cake\ORM\TableRegistry::get('users');
        $user = $usersTable->find()
            ->where(['username' => 'admin'])
            ->first();
        if (! empty($user)) {
            $usersTable->delete($user);
        }
        $user = $usersTable->newEntity([
            'username' => 'adachi',
            'password' => 'kmnkdo',
        ]);
        $usersTable->save($user);
        $user = $usersTable->newEntity([
            'username' => 'manabe',
            'password' => 'hideyukipan',
        ]);
        $usersTable->save($user);
    }

    public function down()
    {
        $usersTable = \Cake\ORM\TableRegistry::get('users');
        $user = $usersTable->newEntity([
            'username' => 'admin',
            'password' => 'hideyukipan',
        ]);
        $usersTable->save($user);
        $usersTable = \Cake\ORM\TableRegistry::get('users');
        $user = $usersTable->find()
            ->where(['username' => 'adachi'])
            ->first();
        if (! empty($user)) {
            $usersTable->delete($user);
        }
        $user = $usersTable->find()
            ->where(['username' => 'manabe'])
            ->first();
        if (! empty($user)) {
            $usersTable->delete($user);
        }
    }
}
