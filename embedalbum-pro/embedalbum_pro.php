<?php

/*
Plugin Name: EmbedSocial - Platform for social media tools
elugin URI: http://www.embedsocial.com
Description: Social media photos and reviews and feeds on your website
Author: EmbedSocial
Author URI: http://www.embedsocial.com
Version: 1.2.1
 */
defined('ABSPATH') or die("No direct script access allowed.");

class EmbedSocialPlugin
{
    private $url = "https://embedsocial.com/facebook_album/";
    private $urlEmbedScripts = "https://embedsocial.com/embedscript/";

    private function sanitizeAtt($att)
    {
        $att = sanitize_text_field(trim($att));
        return sanitize_key($att);
    }

    private function sanitizeTagAtts($atts)
    {
        $acc = [];
        foreach ($atts as $key => $att) {
            $acc[$key] = $this->sanitizeAtt($att);
        }
        return $acc;
    }

    private function loadScript($name, $script, $version = '1.0.1')
    {
        wp_register_script($name, $this->urlEmbedScripts . $script, [], $version, ['in_footer' => false]);
        wp_enqueue_script($name, $this->urlEmbedScripts . $script, [], $version, ['in_footer' => false]);
    }

    public function hook_embed_gallery_js()
    {
        $this->loadScript('EmbedSocialGalleryScript', 'biw.js');
    }

    public function hook_embed_instagram_js()
    {
        $this->loadScript('EmbedSocialInstagramScript', 'in.js');
    }

    public function hook_embed_twitter_js()
    {
        $this->loadScript('EmbedSocialTwitterScript', 'ti.js');
    }

    public function hook_embed_album_js()
    {
        $this->loadScript('EmbedSocialScript', 'eiw.js');
    }

    public function hook_embed_google_album_js()
    {
        $this->loadScript('EmbedSocialGoogleScript', 'gi.js');
    }

    public function hook_embed_socialfeed_js()
    {
        $this->loadScript('EmbedSocialSocialFeedScript', 'sf.js');
    }

    public function hook_embed_reviews_js()
    {
        $this->loadScript('EmbedSocialReviewsScript', 'ri_min.js');
    }

    public function hook_embed_google_reviews_js()
    {
        $this->loadScript('EmbedSocialGoogleReviewsScript', 'gri.js');
    }

    public function hook_embed_custom_reviews_js()
    {
        $this->loadScript('EmbedSocialCustomReviewsScript', 'cri.js');
    }

    public function hook_embed_story_js()
    {
        $this->loadScript('EmbedSocialStoriesScript', 'st.js');
    }

    public function hook_embed_hashtag_js()
    {
        $this->loadScript('EmbedSocialHashtagScript', 'ht.js');
    }

    public function hook_embed_story_popup_js()
    {
        $this->loadScript('EmbedSocialStoriesPopupScript', 'stp.js');
    }

    public function hook_embed_story_gallery_js()
    {
        $this->loadScript('EmbedSocialStoryGalleryScript', 'stg.js');
    }

    private function getEmbedCode($key, $atts)
    {
        $id = $this->getId($atts);
        return "<div class='" . esc_attr($key) . "' data-ref='" . esc_attr($id) . "'></div>";
    }

    private function getId($atts)
    {
        $atts = (shortcode_atts(['id' => ''], $atts));
        $atts = $this->sanitizeTagAtts($atts);
        return $atts['id'];
    }

    public function embedsocial_fb_album_shortcode($atts)
    {
        add_action('wp_footer', [$this, "hook_embed_album_js"]);
        return $this->getEmbedCode('embedsocial-album', $atts);
    }

    public function embedsocial_fb_gallery_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_gallery_js"));
        return $this->getEmbedCode('embedsocial-gallery', $atts);
    }

    public function embedsocial_instagram_album_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_instagram_js"));
        return $this->getEmbedCode('embedsocial-instagram', $atts);
    }

    public function embedsocial_twitter_album_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_twitter_js"));
        return $this->getEmbedCode('embedsocial-twitter', $atts);
    }

    public function embedsocial_google_album_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_google_album_js"));
        return $this->getEmbedCode('embedsocial-google-place', $atts);
    }

    public function embedsocial_feed_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_socialfeed_js"));
        return $this->getEmbedCode('embedsocial-socialfeed', $atts);
    }

    public function embedsocial_reviews_album_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_reviews_js"));

        $atts = (shortcode_atts([
            'id' => '',
            'tags' => '',
            'lazyload' => ''
        ], $atts));
        $atts = $this->sanitizeTagAtts($atts);

        $out = "<div class='embedsocial-reviews' ";
        if ($atts['id']) {
            $out .= " data-ref='" . esc_attr($atts['id']) . "' ";

            if ($atts['tags']) {
                $out .= " data-tags='" . esc_attr($atts['tags']) . "' ";
            }
            if ($atts['lazyload'] && in_array($atts['lazyload'], ['yes', 'no'])) {
                $out .= " data-lazyload='" . esc_attr($atts['lazyload']) . "' ";
            }
            $out .= "></div>";
        }
        return $out;
    }

    public function embedsocial_google_reviews_album_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_google_reviews_js"));
        return $this->getEmbedCode('embedsocial-google-reviews', $atts);
    }

    public function embedsocial_custom_reviews_album_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_custom_reviews_js"));
        return $this->getEmbedCode('embedsocial-custom-reviews', $atts);
    }

    public function embedsocial_stories_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_story_js"));
        return $this->getEmbedCode('embedsocial-stories', $atts);
    }

    public function embedsocial_stories_popup_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_story_popup_js"));
        return $this->getEmbedCode('embedsocial-stories-popup', $atts);
    }

    public function embedsocial_story_gallery_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_story_gallery_js"));
        return $this->getEmbedCode('embedsocial-story-gallery', $atts);
    }

    public function embedsocial_hashtag_shortcode($atts)
    {
        add_action('wp_footer', array($this, "hook_embed_hashtag_js"));
        return $this->getEmbedCode('embedsocial-hashtag', $atts);
    }

    public function embedsocial_schema_shortcode($atts)
    {
        //No longer supported
        return '';
    }

    public function embedsocial_badge_shortcode($atts)
    {
        $atts = (shortcode_atts([
            'id' => '',
            'style' => 'badge-1-g'
        ], $atts));
        $atts = $this->sanitizeTagAtts($atts);

        if ($atts['id']) {
            return  "<div class='reviews-badges'><img src='https://embedsocial.com/api/reviews_badges/" . esc_attr($atts['style']) . "/" . esc_attr($atts['id']) . "'/></div>";
        }

        return '';
    }

    public function embedsocial_badge_custom_shortcode($atts)
    {
        $atts = (shortcode_atts(array(
            'id' => '',
        ), $atts));
        $atts = $this->sanitizeTagAtts($atts);

        if ($atts['id']) {
            return "<iframe src='https://embedsocial.com/api/reviews_source_badges_custom/" . esc_attr($atts['id']) . "' scrolling='no' style='width: 300px; height: 55px; border: 0px; overflow: hidden;' /></iframe>";
        }
        return '';
    }
}

$plugin = new EmbedSocialPlugin();

add_shortcode('embedsocial_schema', array($plugin, 'embedsocial_schema_shortcode'));
add_shortcode('embedsocial_album', array($plugin, 'embedsocial_fb_album_shortcode'));
add_shortcode('embedsocial_gallery', array($plugin, 'embedsocial_fb_gallery_shortcode'));
add_shortcode('embedsocial_instagram', array($plugin, 'embedsocial_instagram_album_shortcode'));
add_shortcode('embedsocial_twitter', array($plugin, 'embedsocial_twitter_album_shortcode'));
add_shortcode('embedsocial_google_album', array($plugin, 'embedsocial_google_album_shortcode'));
add_shortcode('embedsocial_feed', array($plugin, 'embedsocial_feed_shortcode'));
add_shortcode('embedsocial_reviews', array($plugin, 'embedsocial_reviews_album_shortcode'));
add_shortcode('embedsocial_google_reviews', array($plugin, 'embedsocial_google_reviews_album_shortcode'));
add_shortcode('embedsocial_custom_reviews', array($plugin, 'embedsocial_custom_reviews_album_shortcode'));
add_shortcode('embedsocial_stories', array($plugin, 'embedsocial_stories_shortcode'));
add_shortcode('embedsocial_stories_popup', array($plugin, 'embedsocial_stories_popup_shortcode'));
add_shortcode('embedsocial_story_gallery', array($plugin, 'embedsocial_story_gallery_shortcode'));
add_shortcode('embedsocial_hashtag', array($plugin, 'embedsocial_hashtag_shortcode'));
add_shortcode('embedsocial_badge', array($plugin, 'embedsocial_badge_shortcode'));
add_shortcode('embedsocial_badge_custom', array($plugin, 'embedsocial_badge_custom_shortcode'));
