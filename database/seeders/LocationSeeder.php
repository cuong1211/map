<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            // ── TRƯỜNG HỌC ────────────────────────────────────────────────
            [
                'name'        => 'Trường Mầm non Phú Thượng',
                'description' => 'Trường mầm non công lập phục vụ trẻ em độ tuổi mầm non trên địa bàn xóm Phú Thượng, xã Võ Nhai.',
                'address'     => 'Xóm Phú Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.765802636752472,
                'longitude'   => 106.10091919709755,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Tiểu học Phú Thượng',
                'description' => 'Trường tiểu học công lập phục vụ học sinh từ lớp 1 đến lớp 5 trên địa bàn xóm Phú Thượng, xã Võ Nhai.',
                'address'     => 'Xóm Phú Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.762465606173357,
                'longitude'   => 106.08371121642519,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường THCS Phú Thượng',
                'description' => 'Trường trung học cơ sở đào tạo học sinh từ lớp 6 đến lớp 9 trên địa bàn xóm Phú Thượng, xã Võ Nhai.',
                'address'     => 'Xóm Phú Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.759330456902898,
                'longitude'   => 106.08782770000013,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Phổ Thông Dân Tộc Nội Trú Nguyễn Bỉnh Khiêm',
                'description' => 'Trường phổ thông dân tộc nội trú dành cho học sinh là con em đồng bào các dân tộc thiểu số trên địa bàn huyện Võ Nhai.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.755425909486686,
                'longitude'   => 106.08086750000025,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Mầm non Liên cơ',
                'description' => 'Trường mầm non công lập phục vụ trẻ em độ tuổi mầm non thuộc khu vực liên cơ quan trung tâm xã Võ Nhai.',
                'address'     => 'Khu Liên cơ, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.753527187583813,
                'longitude'   => 106.06982150218867,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Tiểu học Đình Cả',
                'description' => 'Trường tiểu học công lập phục vụ học sinh từ lớp 1 đến lớp 5 trên địa bàn xóm Đình Cả, xã Võ Nhai.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.75028733734596,
                'longitude'   => 106.07244649999990,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường THCS Đình Cả',
                'description' => 'Trường trung học cơ sở đào tạo học sinh từ lớp 6 đến lớp 9 trên địa bàn xóm Đình Cả, xã Võ Nhai.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.75084487729299,
                'longitude'   => 106.07451526649517,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Mầm non Lâu Thượng',
                'description' => 'Trường mầm non công lập phục vụ trẻ em độ tuổi mầm non trên địa bàn xóm Lâu Thượng, xã Võ Nhai.',
                'address'     => 'Xóm Lâu Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.74736593581782,
                'longitude'   => 106.05550176033873,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Tiểu học Lâu Thượng',
                'description' => 'Trường tiểu học công lập phục vụ học sinh từ lớp 1 đến lớp 5 trên địa bàn xóm Lâu Thượng, xã Võ Nhai.',
                'address'     => 'Xóm Lâu Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.737726731667003,
                'longitude'   => 106.03453487976287,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường THCS Lâu Thượng',
                'description' => 'Trường trung học cơ sở đào tạo học sinh từ lớp 6 đến lớp 9 trên địa bàn xóm Lâu Thượng, xã Võ Nhai.',
                'address'     => 'Xóm Lâu Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.737488328346835,
                'longitude'   => 106.03393235225573,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trung tâm GDNN - GDTX Võ Nhai',
                'description' => 'Trung tâm Giáo dục nghề nghiệp - Giáo dục thường xuyên huyện Võ Nhai, cung cấp các chương trình đào tạo nghề và bổ túc văn hóa cho người dân địa phương.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.7546954657495,
                'longitude'   => 106.07981100000005,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường THPT Võ Nhai',
                'description' => 'Trường trung học phổ thông đào tạo học sinh từ lớp 10 đến lớp 12, phục vụ con em các dân tộc trên địa bàn huyện Võ Nhai.',
                'address'     => 'Xóm Lâu Thượng, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Trường học',
                'latitude'    => 21.753344311104982,
                'longitude'   => 106.05859039409480,
                'is_active'   => true,
            ],

            // ── CƠ QUAN ───────────────────────────────────────────────────
            [
                'name'        => 'Trung tâm Phục vụ Hành chính công',
                'description' => 'Trung tâm phục vụ hành chính công xã Võ Nhai, đầu mối tiếp nhận và giải quyết các thủ tục hành chính cho người dân và doanh nghiệp trên địa bàn.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Cơ quan',
                'latitude'    => 21.753037021016336,
                'longitude'   => 106.07519093796228,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trung tâm Chính trị xã Võ Nhai',
                'description' => 'Trung tâm chính trị xã Võ Nhai, nơi tổ chức các hoạt động sinh hoạt chính trị, học tập nghị quyết và đào tạo bồi dưỡng cán bộ cơ sở.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Cơ quan',
                'latitude'    => 21.754869330795458,
                'longitude'   => 106.06956734943088,
                'is_active'   => true,
            ],
            [
                'name'        => 'Hội trường xã Võ Nhai',
                'description' => 'Hội trường xã Võ Nhai, nơi tổ chức các cuộc họp, hội nghị của Đảng bộ, HĐND, UBND và các đoàn thể chính trị - xã hội trên địa bàn xã.',
                'address'     => 'Xóm Đình Cả, Xã Võ Nhai, Huyện Võ Nhai, Tỉnh Thái Nguyên',
                'category'    => 'Cơ quan',
                'latitude'    => 21.752942493113444,
                'longitude'   => 106.07093512472255,
                'is_active'   => true,
            ],
        ];

        foreach ($locations as $data) {
            Location::create($data);
        }

        echo "✓ Đã tạo " . \count($locations) . " địa điểm mẫu.\n";
    }
}
