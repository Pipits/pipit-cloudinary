<?php

/**
 * v1.3
 * https://github.com/Pipits/pipit-cloudinary
 */

class PipitTemplateFilter_cloudinary extends PerchTemplateFilter {
    
    public function filterBeforeProcessing($value, $valueIsMarkup = false) {
        if (!defined('CLOUDINARY_CLOUDNAME')) {
            PerchUtil::debug('Cloudinary Name is not set in config', 'notice');
            return $value;
        }

        $allow_dev = false;
        if(defined('PIPIT_CLOUDINARY_DEV')) $allow_dev = PIPIT_CLOUDINARY_DEV;

        if(!$allow_dev) {
            if (PERCH_PRODUCTION_MODE == PERCH_DEVELOPMENT || PERCH_PRODUCTION_MODE == PERCH_STAGING) {
                return $value;
            }
        }



        $pre = 'https://res.cloudinary.com/'. CLOUDINARY_CLOUDNAME .'/image/fetch/';
        $pre_upload = 'https://res.cloudinary.com/'. CLOUDINARY_CLOUDNAME .'/image/upload/';
        $opts = $domain = '';


        // Cloudinary image manipulation options 
        // fetch URLs e.g. /image/fetch/{opts}/{file_url}
        // upload URLs e.g. /image/upload/{opts}/...
        if ($this->Tag->opts) {
            $opts = $this->_replace_vars($this->Tag->opts, $this->content);
            $opts .= '/';
        }


        // is it an upload URL?
        // check if $value starts with $pre_upload
        if(substr($value, 0, strlen($pre_upload)) == $pre_upload) {
            return str_replace($pre_upload, $pre_upload.$opts, $value); 
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



    /**
     * @param string $opts
     * @param array $vars
     * @return string
     */
    private function _replace_vars($opts, $vars) {
        $out =  preg_replace_callback('/{([A-Za-z0-9_\-]+)}/', function($matches) use($vars) {
            if (isset($vars[$matches[1]])) {

                if(!is_array( $vars[$matches[1]] )) {
                    return $vars[$matches[1]];
                } elseif(isset( $vars[$matches[1]]['_default'] )) {
                    return $vars[$matches[1]]['_default'];
                }

            }
        }, $opts);


        // remove rouge commas
        $out = trim($out, ',');
        $out = preg_replace_callback('/[,]{2,}/', function($matches) { return ',';}, $out);
        
        return $out;
    }
}


PerchSystem::register_template_filter('cloudinary', 'PipitTemplateFilter_cloudinary');