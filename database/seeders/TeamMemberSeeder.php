<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevent duplicate records when running seeder multiple times
        TeamMember::query()->delete();

        $members = [
            [
                'name' => 'GS. TS. Lâm Hoài Nam',
                'role' => 'Chủ tịch Hội đồng Khoa học',
                'image' => 'team_members/1.webp',
                'src_image' => 'public/assets/images/team/1.webp',
                'order_column' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'PGS. TS. Trần Thị Mai',
                'role' => 'Phó Viện trưởng / Chuyên gia ESG',
                'image' => 'team_members/2.webp',
                'src_image' => 'public/assets/images/team/2.webp',
                'order_column' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'TS. Phạm Hoàng Nam',
                'role' => 'Trưởng phòng Nghiên cứu Công nghệ Xanh',
                'image' => 'team_members/3.webp',
                'src_image' => 'public/assets/images/team/3.webp',
                'order_column' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'ThS. Lê Thị Thu Hương',
                'role' => 'Chuyên gia Đánh giá Carbon & Khí hậu',
                'image' => 'team_members/4.webp',
                'src_image' => 'public/assets/images/team/4.webp',
                'order_column' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'ThS. Nguyễn Minh Triết',
                'role' => 'Chuyên viên Chuyển giao Công nghệ',
                'image' => 'team_members/5.webp',
                'src_image' => 'public/assets/images/team/5.webp',
                'order_column' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Bà Hoàng Anh Thư',
                'role' => 'Thư ký Hội đồng Chuyên gia',
                'image' => 'team_members/6.webp',
                'src_image' => 'public/assets/images/team/6.webp',
                'order_column' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($members as $member) {
            $srcPath = base_path($member['src_image']);
            if (file_exists($srcPath)) {
                Storage::disk('public')->put($member['image'], file_get_contents($srcPath));
            }
            unset($member['src_image']);
            TeamMember::create($member);
        }
    }
}
