<?php
use Migrations\AbstractMigration;

class IndexForRevisingItems extends AbstractMigration
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
        $table = $this->table('revising_items');
        $table->addIndex('revising_item_header_id')
            ->update();
    }
}
