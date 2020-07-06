<?php
use Migrations\AbstractMigration;

class AutoReviseUsers extends AbstractMigration
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
        $user = $usersTable->find()
            ->where(['username' => 'auto'])
            ->first();
        if ($user) {
            $user = $usersTable->patchEntity($user, [
                'password' => 'kljyim48ef23dft1wag',
            ]);
        } else {
            $user = $usersTable->newEntity([
                'username' => 'auto',
                'password' => 'kljyim48ef23dft1wag',
            ]);
        }
        $usersTable->save($user);
        $user = $usersTable->find()
            ->where(['username' => 'pricing'])
            ->first();
        if ($user) {
            $user = $usersTable->patchEntity($user, [
                'password' => 'kljyim48ef23dft1wag',
            ]);
        } else {
            $user = $usersTable->newEntity([
                'username' => 'pricing',
                'password' => 'kljyim48ef23dft1wag',
            ]);
        }
        $usersTable->save($user);
    }
}
