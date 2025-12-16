<?php

namespace App\Services;

use App\Repositories\TicketRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class TicketService
{
    public function __construct(protected TicketRepositoryInterface $repository)
    {
    }

    public function create(array $data, array $files = [])
    {
        // Create or find customer by phone/email combination
        $customer = Customer::firstOrCreate([
            'phone' => $data['phone'],
        ], [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
        ]);

        $ticketData = [
            'customer_id' => $customer->id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => \App\Models\Ticket::STATUS_NEW,
        ];

        $ticket = $this->repository->create($ticketData);

        // Attach files via medialibrary if available; if it fails, fallback to storing file and creating media row
        if (!empty($files) && method_exists($ticket, 'addMedia')) {
            foreach ($files as $file) {
                try {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                } catch (\Throwable $e) {
                    // Fallback: store file on public disk and create a minimal media record if media table exists
                    try {
                        $fileName = $file->getClientOriginalName();
                        $path = Storage::disk(config('media-library.disk_name', 'public'))->putFileAs('attachments', $file, $fileName);

                        if (Schema::hasTable('media')) {
                            \Illuminate\Support\Facades\DB::table('media')->insert([
                                'model_type' => get_class($ticket),
                                'model_id' => $ticket->id,
                                'collection_name' => 'attachments',
                                'name' => pathinfo($fileName, PATHINFO_FILENAME),
                                'file_name' => $fileName,
                                'mime_type' => $file->getClientMimeType() ?? 'application/octet-stream',
                                'disk' => config('media-library.disk_name', 'public'),
                                'size' => $file->getSize() ?? 0,
                                'custom_properties' => json_encode([]),
                                'generated_conversions' => null,
                                'order_column' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    } catch (\Throwable $e) {
                        // swallow fallback errors to avoid breaking ticket creation
                    }
                }
            }
        }

        return $ticket->fresh('customer');
    }

    public function statistics(): array
    {
        $now = Carbon::now();
        $day = \App\Models\Ticket::whereBetween('created_at', [$now->copy()->subDay(), $now])->count();
        $week = \App\Models\Ticket::whereBetween('created_at', [$now->copy()->subWeek(), $now])->count();
        $month = \App\Models\Ticket::whereBetween('created_at', [$now->copy()->subMonth(), $now])->count();

        return compact('day', 'week', 'month');
    }
}
