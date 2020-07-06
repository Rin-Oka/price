<?php
use Migrations\AbstractMigration;

class CreateReviseHistories extends AbstractMigration
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
        $table = $this->table('revise_histories');
        $table
            ->addColumn('revising_item_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('asin', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('list_price', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('sku', 'string', [
                'default' => '',
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('listing_date', 'date', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('item_condition', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('notes_type', 'string', [
                'default' => '',
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('shipping_type', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('purchase_cost', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('quantities', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('min_purchase_cost', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('max_purchase_cost', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('revise_price', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('revise_price_rank', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('profit', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('user_name', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('offers_json', 'text', [
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'revising_item_id',
                ],
                ['unique' => true]
            )
            ->create();
    }
}
