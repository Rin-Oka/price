<?php
use Migrations\AbstractMigration;

class AlterRecordsForAccounts extends AbstractMigration
{
    public function up()
    {
        $accountsTable = \Cake\ORM\TableRegistry::get('Accounts');
        $account = $accountsTable->find()
            ->where(['name' => 'こじか屋'])
            ->first();
        if ($account instanceof \App\Model\Entity\Account) {
            $accountsTable->patchEntity($account, ['name' => '竜泉BOOKS']);
            $accountsTable->save($account);
        }
        $account = $accountsTable->find()
            ->where(['name' => 'R書店'])
            ->first();
        if ($account instanceof \App\Model\Entity\Account) {
            $accountsTable->patchEntity($account, ['name' => 'スピカ書房']);
            $accountsTable->save($account);
        }
    }

    public function down()
    {
        $accountsTable = \Cake\ORM\TableRegistry::get('Accounts');
        $account = $accountsTable->find()
            ->where(['name' => '竜泉BOOKS'])
            ->first();
        if ($account instanceof \App\Model\Entity\Account) {
            $accountsTable->patchEntity($account, ['name' => 'こじか屋']);
            $accountsTable->save($account);
        }
        $account = $accountsTable->find()
            ->where(['name' => 'スピカ書房'])
            ->first();
        if ($account instanceof \App\Model\Entity\Account) {
            $accountsTable->patchEntity($account, ['name' => 'R書店']);
            $accountsTable->save($account);
        }
    }
}
