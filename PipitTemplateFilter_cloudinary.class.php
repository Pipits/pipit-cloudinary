<?php
class PipitTemplateFilter_cloudinary extends PerchTemplateFilter {
    
    public function filterBeforeProcessing($value, $valueIsMarkup = false) {
        if (!defined('CLOUDINARY_CLOUDNAME')) return $value;
        if (PERCH_PRODUCTION_MODE == PERCH_DEVELOPMENT) return $value;

        $cloudname = CLOUDINARY_CLOUDNAME;
        $pre = 'https://res.cloudinary.com/'. CLOUDINARY_CLOUDNAME .'/image/fetch/';
        $opts = $domain = '';
        $file = $value;


        // Cloudinary image manipulation options
        if ($this->Tag->opts) {
            $opts = $this->Tag->opts .'/';
        }

        
        // Get site URL from settings if required
        if (!$this->Tag->external) {
            $API  = new PerchAPI(1.0, 'pipit');
            $Settings = $API->get('Settings');
            $domain = $Settings->get('siteURL')->val();
            if(substr($domain, -1) == '/') $domain = rtrim($domain, '/');
        }

        
        $value = $pre . $opts . urlencode($domain . $file);
        return $value;
    }
}


PerchSystem::register_template_filter('cloudinary', 'PipitTemplateFilter_cloudinary');