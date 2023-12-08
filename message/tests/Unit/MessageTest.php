<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function users_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('messages', [
            'id', 'user_id', 'heading', 'content', 'created_at', 'updated_at',
        ]), 1);
    }
}
