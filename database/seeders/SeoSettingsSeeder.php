<?php
namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seo_settings')->insert([
            [
                'page_type' => 'home',
                'title' => 'FaNN - Home of Custom Printing & Design',
                'description' => 'Welcome to FaNN, your destination for high-quality custom printing and design solutions. Discover our range of products tailored to your needs.',
                'keywords' => 'custom printing, design, FaNN, personalized products, print shop',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_type' => 'categories',
                'title' => 'Product Categories - FaNN Printing Services',
                'description' => 'Explore our wide range of product categories including business cards, flyers, packaging, and more. High-quality printing at your fingertips.',
                'keywords' => 'printing categories, business cards, flyers, packaging, brochures',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_type' => 'categories-all-products',
                'title' => 'All Printing Products - FaNN',
                'description' => 'Browse all our printing products in one place. From stationery to large-format prints, we’ve got everything you need.',
                'keywords' => 'printing products, print all, custom prints, FaNN products',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_type' => 'about',
                'title' => 'About FaNN - Your Creative Printing Partner',
                'description' => 'FaNN is a professional printing company focused on quality, creativity, and customer satisfaction. Learn about our mission and vision.',
                'keywords' => 'about FaNN, printing company, who we are, printing services',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_type' => 'contact',
                'title' => 'Contact FaNN - Let’s Talk Printing',
                'description' => 'Need help with your order or have a question? Contact FaNN for customer support, inquiries, or business collaborations.',
                'keywords' => 'contact printing, FaNN support, get in touch, customer service',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_type' => 'faq',
                'title' => 'FAQs - FaNN Printing Services',
                'description' => 'Find answers to the most common questions about our printing services, order process, delivery, and more.',
                'keywords' => 'FAQs printing, questions, help, printing process, delivery',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],

               [
                'page_type' => 'offers',
                'title' => 'Special Offers - FaNN Printing Services',
                'description' => 'Discover our latest special offers and discounts on printing services. Save big on your next order with FaNN.',
                'keywords' => 'printing offers, special discounts, FaNN promotions, save on printing',
                'site_name' => 'FaNN',
                'image' => 'https://admin.fan4d.sa/build/images/side_bar_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
//path
