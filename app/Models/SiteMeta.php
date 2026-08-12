<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMeta extends Model
{
    protected $table = 'site_meta';

    protected $fillable = [
        'site_name',
        'site_name_bn',
        'site_title',
        'site_keywords',
        'site_email',
        'site_number',
        'site_logo',
        'site_icon',
        'site_description',
        'primary_color',
        'photocard_title_scale',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'youtube_link',
        'extra_social_links',
        'map_link',
        'map_desc',
        'address_1',
        'editor_name',
        'editor_label',
        'publisher_name',
        'publisher_label',
        'google_adsense_client',
        'google_adsense_default_slot',
        'google_adsense_box_slot',
        'google_adsense_inline_slot',
    ];

    protected $casts = [
        'extra_social_links' => 'array',
    ];

    /**
     * Get the single site meta row. Only one row exists; no auto-create.
     * Returns null if admin has not saved settings yet.
     */
    public static function get(): ?self
    {
        return static::first();
    }
}
