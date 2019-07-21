<?php
class PipitTemplateFilter_cloudinary extends PerchTemplateFilter {
    
    public function filterBeforeProcessing($value, $valueIsMarkup = false) {
        if (!defined('CLOUDINARY_CLOUDNAME')) return $value;
        if (PERCH_PRODUCTION_MODE == PERCH_DEVELOPMENT) return $value;

        $pre = 'https://res.cloudinary.com/'. CLOUDINARY_CLOUDNAME .'/image/fetch/';
        $opts = $domain = '';

        // Cloudinary image manipulation options e.g. /image/fetch/{opts}/{file_url}
        if ($this->Tag->opts) {
            $opts = $this->Tag->opts .'/';
        }

        
        // if URL has no domain, include site's domain
        if (!$this->_is_full_link($value)) {
            $domain = $this->_get_domain();
        } 
        
        
        $value = $pre . $opts . urlencode($domain . $value);
        return $value;
    }



    /**
     * Get domain from config or settings
     * @return string
     */
    private function _get_domain() {
        if(defined('SITE_URL')) {
            $domain = SITE_URL;
        } else {
            $API  = new PerchAPI(1.0, 'pipit');
            $Settings = $API->get('Settings');
            $domain = $Settings->get('siteURL')->val();
        }


        // remove trailing slash
        if(substr($domain, -1) == '/') $domain = rtrim($domain, '/');

        return $domain;
    }



    /**
     * 
     * @return boolean
     */
    private function _is_full_link($link) {
        $file_url_parts = parse_url($link);

        // value includes a domain host
        if(isset($file_url_parts['host'])) {
            return true;
        }

        return false;
    }
}


PerchSystem::register_template_filter('cloudinary', 'PipitTemplateFilter_cloudinary');