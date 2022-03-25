<?php

/**
 * Repo: https://github.com/r0ld3x/google-translater-php
 * @author r0ld3x
 */


class Translate
{
    const VERSION = '1.0';

    private $langs_code = ['auto' => 'Automatic', 'af' => 'Afrikaans', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'hy' => 'Armenian', 'az' => 'Azerbaijani', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali', 'bs' => 'Bosnian', 'bg' => 'Bulgarian', 'ca' => 'Catalan', 'ceb' => 'Cebuano', 'ny' => 'Chichewa', 'zh-cn' => 'Chinese Simplified', 'zh-tw' => 'Chinese Traditional', 'co' => 'Corsican', 'hr' => 'Croatian', 'cs' => 'Czech', 'da' => 'Danish', 'nl' => 'Dutch', 'en' => 'English', 'eo' => 'Esperanto', 'et' => 'Estonian', 'tl' => 'Filipino', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Frisian', 'gl' => 'Galician', 'ka' => 'Georgian', 'de' => 'German', 'el' => 'Greek', 'gu' => 'Gujarati', 'ht' => 'Haitian Creole', 'ha' => 'Hausa', 'haw' => 'Hawaiian', 'iw' => 'Hebrew', 'hi' => 'Hindi', 'hmn' => 'Hmong', 'hu' => 'Hungarian', 'is' => 'Icelandic', 'ig' => 'Igbo', 'id' => 'Indonesian', 'ga' => 'Irish', 'it' => 'Italian', 'ja' => 'Japanese', 'jw' => 'Javanese', 'kn' => 'Kannada', 'kk' => 'Kazakh', 'km' => 'Khmer', 'ko' => 'Korean', 'ku' => 'Kurdish (Kurmanji)', 'ky' => 'Kyrgyz', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish', 'mk' => 'Macedonian', 'mg' => 'Malagasy', 'ms' => 'Malay', 'ml' => 'Malayalam', 'mt' => 'Maltese', 'mi' => 'Maori', 'mr' => 'Marathi', 'mn' => 'Mongolian', 'my' => 'Myanmar (Burmese)', 'ne' => 'Nepali', 'no' => 'Norwegian', 'ps' => 'Pashto', 'fa' => 'Persian', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ma' => 'Punjabi', 'ro' => 'Romanian', 'ru' => 'Russian', 'sm' => 'Samoan', 'gd' => 'Scots Gaelic', 'sr' => 'Serbian', 'st' => 'Sesotho', 'sn' => 'Shona', 'sd' => 'Sindhi', 'si' => 'Sinhala', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'so' => 'Somali', 'es' => 'Spanish', 'su' => 'Sundanese', 'sw' => 'Swahili', 'sv' => 'Swedish', 'tg' => 'Tajik', 'ta' => 'Tamil', 'te' => 'Telugu', 'th' => 'Thai', 'tr' => 'Turkish', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek', 'vi' => 'Vietnamese', 'cy' => 'Welsh', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'zu' => 'Zulu'];
    
    private $url_traduc = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=%s&tl=%s&dt=t&q=%s';

    private $error        = false;
    private $error_string = '';

    private $took = 0;

    public $input_text  = '';
    public $output_text = '';
    public $input_lang  = '';
    public $output_lang = '';


    
    private function check() {
        
        if (empty($this->input_text)) {
            $this->error = TRUE;
            $this->error_string = 'Put text to translate';
        }

        if (!isset($this->langs_code[$this->input_lang])) {
            $this->error = TRUE;
            $this->error_string = 'Invalid lang code input ('.$this->input_lang.')';
        }

        if (!isset($this->langs_code[$this->output_lang])) {
            $this->error = TRUE;
            $this->error_string = 'Invalid lang code output ('.$this->output_lang.')';
        }
        
    }


    /**
     * Translate Text To Another Language With Google's Translate Api.
     * @param string $input_text
     * @param string $input_lang
     * @param string $output_lang
     */
    private function translate1() {

        $encode_text = urlencode($this->input_text);
        $url = sprintf($this->url_traduc, $this->input_lang, $this->output_lang, $encode_text);
        $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
	    curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');
		$output = curl_exec($ch);
		curl_close($ch);

        if (empty($output)) {
            $this->error = TRUE;
            $this->error_string = 'Unknown error, try again';
        } else {
            $response = json_decode($output);
		    $lineas = count($response[0]);
		    $content = '';
		    for ($i=0; $i < $lineas; $i++) { 
		        $content .=  $response[0][$i][0];
		    }
		    $lang_in = $response['2'];

            if ($lang_in == $this->output_lang) {
                $this->error = TRUE;
                $this->error_string = 'The text is already in the language you want to translate ('.$this->langs_code[$lang_in].')';
            } else {
                $this->output_text = $content;
                $this->input_lang  = $this->langs_code[$lang_in];
                $this->output_lang = $this->langs_code[$this->output_lang];
            }

        }

    }

    /** 
     * Translate Text To Another Language.
     * @param string text
     * @param string lang_input
     * @param string lang_output
    */
    public function tr($in_text, $in_lang = 'auto', $ou_lang = 'en') {
        
        $this->input_text  = $in_text;
        $this->input_lang  = $in_lang;
        $this->output_lang = $ou_lang;
        $this->check();

        $this->took = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4);

        if ($this->error == TRUE) {

            return (object) [
                'error' => $this->error,
                'msg'   => $this->error_string,
                'took'  => $this->took
            ];

        }

        $this->translate1();

        if ($this->error == TRUE) {

            return (object) [
                'error' => $this->error,
                'msg'   => $this->error_string,
                'took'  => $this->took
            ];

        } else {

            return (object) [
                // 'error' => $this->error,
                'took'  => $this->took,
                'input' => (object) [
                    'text' => $this->input_text,
                    'lang' => $this->input_lang,
                ],
                'output' => (object) [
                    'text' => $this->output_text,
                    'lang' => $this->output_lang,
                ],
            ];

        }
        

    }

}


?>