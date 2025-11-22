<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userA = User::create([
            'name' => 'User A',
            'email' => 'usera@example.com',
            'password' => bcrypt('password123'),
            'username' => 'usera',
        ]);

        $userB = User::create([
            'name' => 'User B',
            'email' => 'userb@example.com',
            'password' => bcrypt('password123'),
            'username' => 'userb',
        ]);

        // 🧩 Bước 2: Tạo conversation (direct chat)
        $conversation = DB::table('conversations')->insertGetId([
            'type' => 'direct',
            'created_by' => $userA->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🧩 Bước 3: Gắn participants vào conversation
        // Nếu có bảng conversation_participants, uncomment phần này
        // DB::table('conversation_participants')->insert([
        //     ['conversation_id' => $conversation, 'user_id' => $userA->id, 'created_at' => now()],
        //     ['conversation_id' => $conversation, 'user_id' => $userB->id, 'created_at' => now()],
        // ]);

        // 🧩 Bước 4: Tạo vài tin nhắn test
        DB::table('messages')->insert([
            [
                'sender_id' => $userA->id,
                'conversation_id' => $conversation,
                'content' => 'Xin chào! Bạn khỏe không?',
                'status' => 'seen',
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ],
            [
                'sender_id' => $userB->id,
                'conversation_id' => $conversation,
                'content' => 'Chào bạn! Mình khỏe, còn bạn?',
                'status' => 'seen',
                'created_at' => now()->subMinutes(8),
                'updated_at' => now()->subMinutes(8),
            ],
            [
                'sender_id' => $userA->id,
                'conversation_id' => $conversation,
                'content' => 'Mình cũng khỏe, hôm nay bận không?',
                'status' => 'delivered',
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
            [
                'sender_id' => $userB->id,
                'conversation_id' => $conversation,
                'content' => 'Không bận, nói chuyện được không?',
                'status' => 'sent',
                'created_at' => now()->subMinutes(2),
                'updated_at' => now()->subMinutes(2),
            ],
        ]);

        $this->command->info('✅ Chat test data created successfully!');
    }
}
