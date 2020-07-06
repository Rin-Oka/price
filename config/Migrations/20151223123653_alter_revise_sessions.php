<?php
use Migrations\AbstractMigration;

class AlterReviseSessions extends AbstractMigration
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
        $table = $this->table('revise_sessions');
        $table->changeColumn('latest_uploaded', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
            ->update();
    }

    public function down()
    {
        $table = $this->table('revise_sessions');
        $table->changeColumn('latest_uploaded', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => false,
        ])
            ->update();
    }
}
