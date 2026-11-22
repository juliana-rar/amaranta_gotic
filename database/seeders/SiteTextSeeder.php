<?php

namespace Database\Seeders;

use App\Models\SiteText;
use App\Support\SiteTextCatalog;
use Illuminate\Database\Seeder;

class SiteTextSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteTextCatalog::definitions() as $definition) {
            SiteText::firstOrCreate(['key' => $definition['key']], ['value' => $definition['value']]);
        }
    }
}
