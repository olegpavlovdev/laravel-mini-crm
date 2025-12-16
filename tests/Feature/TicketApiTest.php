<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Ticket;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_can_be_created_with_attachments()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+12345678901',
            'subject' => 'Test subject',
            'message' => 'Test message',
            'files' => [$file],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tickets', ['subject' => 'Test subject']);

        // If medialibrary is installed, media entry should exist
        if (\Schema::hasTable('media')) {
            $this->assertDatabaseHas('media', ['file_name' => 'doc.pdf']);
        }
    }

    public function test_rate_limiting_prevents_quick_submissions()
    {
        $payload = [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'phone' => '+19876543210',
            'subject' => 'Rate test',
            'message' => 'Hello',
        ];

        $this->postJson('/api/tickets', $payload)->assertStatus(200);
        $this->postJson('/api/tickets', $payload)->assertStatus(429);
    }

    public function test_statistics_endpoint_returns_counts()
    {
        // Create tickets for different dates
        Ticket::factory()->create(['created_at' => now()]);
        Ticket::factory()->create(['created_at' => now()->subDays(2)]);
        Ticket::factory()->create(['created_at' => now()->subWeeks(2)]);

        $response = $this->getJson('/api/tickets/statistics');

        $response->assertStatus(200);
        $response->assertJsonStructure(['day', 'week', 'month']);
    }
}
