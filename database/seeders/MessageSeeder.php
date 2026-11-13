<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Aiman Hakim',
                'email' => 'aiman.hakim@example.com',
                'subject' => 'Laravel & Livewire SaaS Consultation',
                'message' => 'Hi Nizar, we are looking for an experienced Laravel & Livewire developer to help modernize our internal operations portal. Would you be available for a quick discovery call this week?',
                'is_read' => false,
            ],
            [
                'name' => 'Sarah Lim',
                'email' => 'sarah.lim@example.com',
                'subject' => 'Legacy PHP Migration Inquiry',
                'message' => 'Hello Nizar, we saw your portfolio and experience with legacy asset migration and MySQL optimization. Let us know your availability for a 3-month contract engagement.',
                'is_read' => true,
            ],
        ];

        foreach ($messages as $message) {
            Message::firstOrCreate(
                [
                    'email' => $message['email'],
                    'subject' => $message['subject'],
                ],
                $message,
            );
        }
    }
}
