<?php

namespace Auth;

use Phoenix\Migration\AbstractMigration;

class AuthMigration extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('auth')
            ->addColumn('user_id', 'integer')
            ->addColumn('token', 'string', ['length' => 200])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addForeignKey('user_id', 'users')
            ->create();
    }

    protected function down(): void
    {
        $this->table('auth')->drop();
    }
}
