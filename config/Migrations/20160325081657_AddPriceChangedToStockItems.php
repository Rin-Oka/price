<?php
use Migrations\AbstractMigration;

class AddPriceChangedToStockItems extends AbstractMigration
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
            ->addColumn('price_changed', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
                'after' => 'is_fba',
            ])
            ->update();
    }
}
