<?php
use Migrations\AbstractMigration;

class AddIsNewlyListedToStockItems extends AbstractMigration
{

    public function up()
    {

        $this->table('stock_items')
            ->addColumn('is_newly_listed', 'boolean', [
                'after' => 'price_changed',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->update();
    }

    public function down()
    {

        $this->table('stock_items')
            ->removeColumn('is_newly_listed')
            ->update();
    }
}

