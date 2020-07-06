<?php
use Migrations\AbstractMigration;

class RecordsForAccounts extends AbstractMigration
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
        $accountsTable = \Cake\ORM\TableRegistry::get('accounts');
        $account = $accountsTable->find()
            ->where(['name' => '熊猫堂'])
            ->first();
        if (empty($account)) {
            $account = $accountsTable->newEntity([
                'name' => '熊猫堂',
                'seller_code' => 'A2EON6VM8N56Z2',
            ]);
            $accountsTable->save($account);
        }

        $account = $accountsTable->find()
            ->where(['name' => 'クローバー'])
            ->first();
        if (empty($account)) {
            $account = $accountsTable->newEntity([
                'name' => 'クローバー',
                'seller_code' => 'A11RCYDUIV69S0',
            ]);
            $accountsTable->save($account);
        }

        $account = $accountsTable->find()
            ->where(['name' => 'こじか屋'])
            ->first();
        if (empty($account)) {
            $account = $accountsTable->newEntity([
                'name' => 'こじか屋',
                'seller_code' => 'A13IY8XV1MNEAZ',
            ]);
            $accountsTable->save($account);
        }

        $account = $accountsTable->find()
            ->where(['name' => 'R書店'])
            ->first();
        if (empty($account)) {
            $account = $accountsTable->newEntity([
                'name' => 'R書店',
                'seller_code' => 'A2C5EZY4V06N0A',
            ]);
            $accountsTable->save($account);
        }


        $account = $accountsTable->find()
            ->where(['name' => 'むげん堂'])
            ->first();
        if (empty($account)) {
            $account = $accountsTable->newEntity([
                'name' => 'むげん堂',
                'seller_code' => 'AUDII0TR5YEWR',
            ]);
            $accountsTable->save($account);
        }

    }

    public function down()
    {
        $accountsTable = \Cake\ORM\TableRegistry::get('accounts');
        /** @var \Cake\Database\Connection $connection */
        $connection = $accountsTable->connection();
        $connection->query('TRUNCATE TABLE `accounts`;');
    }
}
