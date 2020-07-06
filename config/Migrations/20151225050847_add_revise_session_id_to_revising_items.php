<?php
use Migrations\AbstractMigration;

class AddReviseSessionIdToRevisingItems extends AbstractMigration
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
            ->addColumn('revise_session_id', 'integer', [
                'default' => null,
                'null' => false,
                'after' => 'id',
            ])
            ->update();
    }

    public function down()
    {
        $this->table('revising_items')
            ->removeColumn('revise_session_id')
            ->update();
    }
}
