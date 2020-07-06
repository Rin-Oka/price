<?php
use Migrations\AbstractMigration;

class AddIsFbaToStockItems extends AbstractMigration
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
        $table = $this->table('stock_items');
        $table
            ->addColumn('is_fba', 'boolean', [
                'default' => 0,
                'null' => false,
                'after' => 'account_id',
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'after' => 'is_fba',
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'after' => 'created',
            ])
            ->update();
    }
}
