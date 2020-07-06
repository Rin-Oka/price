<?php
use Migrations\AbstractMigration;

class AddPurchaseChannelToStockItems extends AbstractMigration
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
            ->addColumn('purchase_channel', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
                'after' => 'is_fba',
            ])
            ->update();
        $table = $this->table('revising_items');
        $table
            ->addColumn('purchase_channel', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
                'after' => 'is_fba',
            ])
            ->update();
    }
}
