<?php
use Migrations\AbstractMigration;

class CreatePrices extends AbstractMigration
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
        $table = $this->table('prices');
        $table
            ->addColumn('sku', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('account_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('price', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('is_manual', 'boolean', [
                'default' => 0,
                'limit' => 1,
                'null' => false,
            ])
            ->addColumn('uploaded', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('submission_code', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => true,
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
                    'sku',
                ],
                ['unique' => true]
            )
            ->create();

        $table
            ->addForeignKey(
                'account_id',
                'accounts',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('prices')
            ->dropForeignKey(
                'account_id'
            );
        $this->dropTable('prices');
    }
}
