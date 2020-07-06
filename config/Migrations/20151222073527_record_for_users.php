<?php
use Migrations\AbstractMigration;

class RecordForUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $usersTable = \Cake\ORM\TableRegistry::get('users');
        $user = $usersTable->newEntity([
            'username' => 'admin',
            'password' => 'hideyukipan',
        ]);
        $usersTable->save($user);
    }

    public function down()
    {
        $usersTable = \Cake\ORM\TableRegistry::get('users');
        $user = $usersTable->find()
            ->where(['username' => 'admin'])
            ->first();
        if (! empty($user)) {
            $usersTable->delete($user);
        }
    }
}
