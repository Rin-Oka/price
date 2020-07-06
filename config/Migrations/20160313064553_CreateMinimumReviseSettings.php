<?php
use Migrations\AbstractMigration;

class CreateMinimumReviseSettings extends AbstractMigration
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
        $table = $this->table('minimum_revise_settings');
        $table
            ->addColumn('item_condition', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('range', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('ignore_acceptable', 'boolean', [
                'default' => 0,
                'limit' => 1,
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
                    'item_condition',
                ],
                ['unique' => true]
            )
            ->create();
        $table = $this->table('revising_items');
        $table
            ->addColumn('revising_price_rank', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'after' => 'revising_price',
            ])
            ->update();
    }
}
