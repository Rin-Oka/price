<?php
use Migrations\AbstractMigration;

class AddHasFbaToAccounts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('accounts');
        $table
            ->addColumn('has_fba', 'boolean', [
                'default' => 0,
                'null' => false,
                'after' => 'seller_code',
            ])
            ->update();
    }
}
