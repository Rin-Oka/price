<?php
use Migrations\AbstractMigration;

class AddIsExcludedToStockItemHeaders extends AbstractMigration
{

    public function up()
    {

        $this->table('stock_item_headers')
            ->addColumn('is_excluded', 'boolean', [
                'after' => 'latest_offers_loaded',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->update();
    }

    public function down()
    {

        $this->table('stock_item_headers')
            ->removeColumn('is_excluded')
            ->update();
    }
}

