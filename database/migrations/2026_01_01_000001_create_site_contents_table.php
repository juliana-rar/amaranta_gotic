<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            foreach ([
                'hero_eyebrow', 'hero_title', 'hero_subtitle',
                'stat1_number', 'stat1_label', 'stat2_number', 'stat2_label',
                'stat3_number', 'stat3_label', 'stat4_number', 'stat4_label',
                'feature1_icon', 'feature1_title', 'feature1_description',
                'feature2_icon', 'feature2_title', 'feature2_description',
                'feature3_icon', 'feature3_title', 'feature3_description',
                'category1_image', 'category1_title', 'category1_description',
                'category2_image', 'category2_title', 'category2_description',
                'category3_image', 'category3_title', 'category3_description',
                'category4_image', 'category4_title', 'category4_description',
                'story_eyebrow', 'story_title', 'story_paragraph1', 'story_paragraph2',
                'story_quote', 'story_quote_author',
                'location_address', 'location_hours',
                'contact_phone', 'contact_email', 'contact_instagram',
            ] as $column) {
                $table->text($column)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contents');
    }
};
