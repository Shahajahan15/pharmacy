<?php


class MY_Form_validation extends BF_Form_validation {


    public function valid_date_format($str, $format)
    {
        $this->CI->form_validation->set_message('valid_date_format', lang('wt_form_valid_date_format'));

        return \DateTime::createFromFormat($format, $str) !== FALSE;
    }

    public function valid_time_format($str, $format)
    {
        $this->CI->form_validation->set_message('valid_time_format', lang('wt_form_valid_time_format'));

        return \DateTime::createFromFormat($format, $str) !== FALSE;
    }

    public function valid_datetime_format($str, $format)
    {
        $this->CI->form_validation->set_message('valid_datetime_format', lang('wt_form_valid_datetime_format'));

        return \DateTime::createFromFormat($format, $str) !== FALSE;
    }

}
