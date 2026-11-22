<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    public $timestamps = false;

    protected $fillable = [
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
        'story_eyebrow', 'story_title', 'story_paragraph1', 'story_paragraph2', 'story_quote', 'story_quote_author',
        'location_address', 'location_hours',
        'contact_phone', 'contact_email', 'contact_instagram',
    ];

    protected $attributes = [
        'hero_eyebrow' => '', 'hero_title' => '', 'hero_subtitle' => '',
        'stat1_number' => '', 'stat1_label' => '', 'stat2_number' => '', 'stat2_label' => '',
        'stat3_number' => '', 'stat3_label' => '', 'stat4_number' => '', 'stat4_label' => '',
        'feature1_icon' => '', 'feature1_title' => '', 'feature1_description' => '',
        'feature2_icon' => '', 'feature2_title' => '', 'feature2_description' => '',
        'feature3_icon' => '', 'feature3_title' => '', 'feature3_description' => '',
        'category1_image' => '', 'category1_title' => '', 'category1_description' => '',
        'category2_image' => '', 'category2_title' => '', 'category2_description' => '',
        'category3_image' => '', 'category3_title' => '', 'category3_description' => '',
        'category4_image' => '', 'category4_title' => '', 'category4_description' => '',
        'story_eyebrow' => '', 'story_title' => '', 'story_paragraph1' => '', 'story_paragraph2' => '',
        'story_quote' => '', 'story_quote_author' => '',
        'location_address' => '', 'location_hours' => '',
        'contact_phone' => '', 'contact_email' => '', 'contact_instagram' => '',
    ];
}
