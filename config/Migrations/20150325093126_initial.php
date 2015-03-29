<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {

		$table = $this->table('files');
    $table
      ->addColumn('id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('user_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('title', 'string', [
        'limit' => '255', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('filename', 'string', [
        'limit' => '255', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('type', 'string', [
        'limit' => '50', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('size', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('ext', 'string', [
        'limit' => '50', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('dir', 'string', [
        'limit' => '255', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('created', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('modified', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->save();

		$table = $this->table('users');
    $table
      ->addColumn('id', 'integer', [
        'limit' => '10', 
        'unsigned' => '1', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('file_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('email', 'string', [
        'limit' => '50', 
        'null' => '1', 
        'default' => '', 
      ])
      ->addColumn('password', 'string', [
        'limit' => '255', 
        'null' => '1', 
        'default' => '', 
      ])
      ->addColumn('first', 'string', [
        'limit' => '150', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('last', 'string', [
        'limit' => '150', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('role', 'string', [
        'limit' => '20', 
        'null' => '1', 
        'default' => 'user', 
      ])
      ->addColumn('validated', 'integer', [
        'limit' => '4', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '0', 
      ])
      ->addColumn('token', 'string', [
        'limit' => '255', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('token_creation', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('created', 'datetime', [
        'limit' => '', 
        'null' => '1', 
        'default' => '', 
      ])
      ->addColumn('modified', 'datetime', [
        'limit' => '', 
        'null' => '1', 
        'default' => '', 
      ])
      ->save();
    }

    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up()
    {
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
    }

}
