<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name'        => 'Ủy ban nhân dân xã Ba Chẽ',
                'description' => 'Trụ sở UBND xã Ba Chẽ - cơ quan hành chính nhà nước cấp xã, phụ trách quản lý các hoạt động kinh tế - xã hội trên địa bàn.',
                'address'     => 'Khu 1, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Hành chính',
                'latitude'    => 21.5215,
                'longitude'   => 107.1980,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường Tiểu học Ba Chẽ',
                'description' => 'Trường tiểu học công lập phục vụ học sinh từ lớp 1 đến lớp 5 trên địa bàn xã Ba Chẽ và các xã lân cận.',
                'address'     => 'Khu 2, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Giáo dục',
                'latitude'    => 21.5230,
                'longitude'   => 107.2010,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trạm Y tế xã Ba Chẽ',
                'description' => 'Trạm y tế cung cấp dịch vụ chăm sóc sức khỏe ban đầu, tiêm chủng, khám chữa bệnh cơ bản cho người dân địa phương.',
                'address'     => 'Khu 1, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Y tế',
                'latitude'    => 21.5198,
                'longitude'   => 107.1995,
                'is_active'   => true,
            ],
            [
                'name'        => 'Chợ Ba Chẽ',
                'description' => 'Chợ truyền thống nơi người dân trao đổi hàng hóa, nông sản, đặc sản địa phương. Họp chợ vào các ngày trong tuần.',
                'address'     => 'Trung tâm Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Kinh doanh',
                'latitude'    => 21.5245,
                'longitude'   => 107.2025,
                'is_active'   => true,
            ],
            [
                'name'        => 'Suối Cái Ba Chẽ',
                'description' => 'Điểm du lịch sinh thái tự nhiên với dòng suối trong xanh, phù hợp cho hoạt động trekking, cắm trại và trải nghiệm thiên nhiên.',
                'address'     => 'Thôn Đồng Mua, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Du lịch',
                'latitude'    => 21.5350,
                'longitude'   => 107.2150,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trường THCS Ba Chẽ',
                'description' => 'Trường trung học cơ sở đào tạo học sinh từ lớp 6 đến lớp 9. Trường được đầu tư đầy đủ cơ sở vật chất và trang thiết bị học tập.',
                'address'     => 'Khu 3, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Giáo dục',
                'latitude'    => 21.5270,
                'longitude'   => 107.2040,
                'is_active'   => true,
            ],
            [
                'name'        => 'Đình làng Ba Chẽ',
                'description' => 'Di tích lịch sử văn hóa cấp huyện. Đình làng là nơi sinh hoạt cộng đồng và tổ chức các lễ hội truyền thống hằng năm.',
                'address'     => 'Thôn Ba Chẽ, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Du lịch',
                'latitude'    => 21.5185,
                'longitude'   => 107.1960,
                'is_active'   => true,
            ],
            [
                'name'        => 'Trung tâm Văn hóa - Thể thao xã Ba Chẽ',
                'description' => 'Trung tâm tổ chức các hoạt động văn hóa, thể thao, vui chơi giải trí phục vụ đời sống tinh thần của người dân địa phương.',
                'address'     => 'Khu Trung tâm, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Hành chính',
                'latitude'    => 21.5220,
                'longitude'   => 107.1990,
                'is_active'   => true,
            ],
            [
                'name'        => 'Cửa hàng Nông sản Ba Chẽ',
                'description' => 'Cửa hàng kinh doanh các sản phẩm nông nghiệp đặc trưng của Ba Chẽ: ba kích tím, trà hoa vàng, mật ong rừng và các loại thảo dược quý.',
                'address'     => 'Đường chính, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Kinh doanh',
                'latitude'    => 21.5240,
                'longitude'   => 107.2005,
                'is_active'   => true,
            ],
            [
                'name'        => 'Vườn Trà Hoa Vàng Ba Chẽ',
                'description' => 'Vườn trồng trà hoa vàng - đặc sản quý hiếm của huyện Ba Chẽ. Du khách có thể tham quan, tìm hiểu quy trình chế biến và mua sản phẩm trực tiếp.',
                'address'     => 'Thôn Lương Mông, Xã Ba Chẽ, Huyện Ba Chẽ, Tỉnh Quảng Ninh',
                'category'    => 'Du lịch',
                'latitude'    => 21.5160,
                'longitude'   => 107.1920,
                'is_active'   => true,
            ],
        ];

        foreach ($locations as $data) {
            Location::create($data);
        }

        echo "✓ Đã tạo " . count($locations) . " địa điểm mẫu.\n";
    }
}
