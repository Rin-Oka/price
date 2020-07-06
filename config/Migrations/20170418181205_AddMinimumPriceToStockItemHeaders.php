<?php
use Migrations\AbstractMigration;

class AddMinimumPriceToStockItemHeaders extends AbstractMigration
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
        $table = $this->table('stock_item_headers');
        $table
            ->addColumn('minimum_price', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'after' => 'revise_history_json',
            ])
            ->update();
    }
}
