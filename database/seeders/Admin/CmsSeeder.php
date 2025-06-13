<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\Cms;
class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cmsData = [
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<p>These are the terms and conditions...</p>',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<p>This is the privacy policy...</p>',
            ],
            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'content' => '<p>This is the refund policy...</p>',
            ],
        ];

       Cms::insert($cmsData);
    }
}
