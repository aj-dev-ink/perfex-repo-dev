<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('_getCityOptions')) {
    function _getCityOptions(){
        return [
            ['id' => 1, 'name'=> 'Pune'], 
            ['id' => 2, 'name'=> 'Mumbai'],
            ['id' => 3, 'name'=> 'Banglore'] 
        ];
    }
}

if (!function_exists('_getLeadTypeOptions')) {
    function _getLeadTypeOptions(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_lead_type');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found
    }


}

if (!function_exists('_getUserOptions')) {
    function _getUserOptions(){        

        $CI = &get_instance();
        $CI->db->select('staffid as id, CONCAT(firstname, " ", lastname) as name');
        $CI->db->from( db_prefix() . 'staff');
        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();  // Return all staff as an array of options
        }

        return [];  // Return empty array if no staff found
    }
}

if (!function_exists('_getSalutationOptions')) {
    function _getSalutationOptions(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_salutation');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found        
    }
}

if (!function_exists('_getTimeZoneOptions')) {
    function _getTimeZoneOptions(){
        return [
            ['id' => 1, 'name'=> 'GMT'], 
            ['id' => 2, 'name'=> 'PST'],
            ['id' => 3, 'name'=> 'EST'] 
        ];
    }
}

if (!function_exists('_getYesNoOptions')) {
    function _getYesNoOptions(){
        return [
            ['id' => 1, 'name'=> 'Yes'], 
            ['id' => 2, 'name'=> 'No'] 
        ];        
    }
}

if (!function_exists('_getPipelineOptions')) {
    function _getPipelineOptions(){
        return [
            ['id' => 1, 'name'=> 'Pipeline 1'], 
            ['id' => 2, 'name'=> 'Pipeline 2'],
            ['id' => 3, 'name'=> 'Pipeline 3'] 
        ];        
    }
}

if (!function_exists('_getCountryOptions')) {
    function _getCountryOptions(){

        $CI = &get_instance();
        $CI->db->select('country_id as id, short_name as name');
        $CI->db->from( db_prefix() . 'countries');
        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();// Return all records as array options
        }

        return [];  // Return empty array

    }
}

if (!function_exists('_getCompEmployeeOptions')) {
    function _getCompEmployeeOptions(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_company_employees');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found
    }
}

if (!function_exists('_getCompIndustryOptions')) {
    function _getCompIndustryOptions(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_industry');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found
    }
}

if (!function_exists('_getCurrencyOptions')) {
    function _getCurrencyOptions(){
        return [
            ['id' => 1, 'name'=> 'USD'], 
            ['id' => 2, 'name'=> 'EUR'],
            ['id' => 3, 'name'=> 'GBP'] 
        ];        
    }
}

if (!function_exists('_getProductServicesOptions')) {
    function _getProductServicesOptions(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_products_or_services');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found
    }
}

if (!function_exists('_getCampaignOptions')) {
    function _getCampaignOptions(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_campaign_name');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found
    }
}

if (!function_exists('_getLeadSourceOptions')) {
    function _getLeadSourceOptions(){
        //tblleads_sources
        $CI = &get_instance();
        $CI->db->select('id as id, name as name');
        //$CI->db->select('staffid as id, CONCAT(firstname, " ", lastname) as name');

        $CI->db->from( db_prefix() . 'leads_sources');
        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();  // Return all source as an array of options
        }

        return [];  // Return empty array if no source found        
    }
}

if (!function_exists('_getBuissnessUnit')) {
    function _getBuissnessUnit(){
        $CI = &get_instance();
        $CI->db->select('*');
        $CI->db->from( db_prefix() . 'customfields');
        $CI->db->where('slug', 'leads_business_unit');

        $query = $CI->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();  // Return all option as an array of options

            $options = explode(',', $result[0]['options']);
            $arrOptions = [];
            foreach( $options as $key => $value ) {
                $arrOptions[] = [
                    'id' => $value,
                    'name' => $value
                ];
            }
            return $arrOptions;
        }
        return [];  // Return empty array if no option found
    }
}

if (!function_exists('_getForastingType')) {
    function _getForastingType(){
        return [
            ['id' => 1, 'name'=> 'Forasting Type 1'], 
            ['id' => 2, 'name'=> 'Forasting Type 2'],
            ['id' => 3, 'name'=> 'Forasting Type 3'] 
        ];        
    }
}
