<?php
use Migrations\AbstractMigration;

class ChangeUniqueForRevisingItems extends AbstractMigration
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
        $table = $this->table('revising_items');
        $table
            ->removeIndex(['sku'])
            ->addIndex(
                ['sku', 'revising_item_header_id'],
                ['unique' => true]
            )
            ->update();
    }

    public function down(){
        $table = $this->table('revising_items');
        $table
            ->removeIndex(['sku', 'revising_item_header_id'])
            ->addIndex(
                ['sku'],
                ['unique' => true]
            )
            ->update();
    }
}
