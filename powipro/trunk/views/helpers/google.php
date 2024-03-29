<?php

class GoogleHelper extends AppHelper {
    //    This Helper Based on Jamie Telin's (jamie.telin@gmail.com) GoogleTranslateApi v1.1
    //
    //    $google->FromLang = 'he';
    //    $google->ToLang = 'en';
    //    echo $google->translate('×©×œ×•× ×¢×•×œ×!');
    //    API version might change, so change $Version if needed
    //    $google->DebugMsg //Gets all error messages
    //    $google->DebugStatus //Gets all status codes, 200 = ok, 400 = Invalid languages

    var $BaseUrl = 'http://ajax.googleapis.com/ajax/services/language/translate';
    var $FromLang = 'de';
    var $ToLang = 'en';
    var $Version = '1.0';

    var $CallUrl;

    var $Text = '×©×œ×•× ×¢×•×œ×!';

    var $TranslatedText;
    var $DebugMsg;
    var $DebugStatus;

    function makeCallUrl(){
        $this->CallUrl = $this->BaseUrl;
        $this->CallUrl .= "?v=" . $this->Version;
        $this->CallUrl .= "&q=" . urlencode($this->Text);
        $this->CallUrl .= "&langpair=" . $this->FromLang;
        $this->CallUrl .= "%7C" . $this->ToLang;
    }

    function translate($text = ''){
        if($text != ''){
            $this->Text = $text;
        }
        $this->makeCallUrl();
        if($this->Text != '' && $this->CallUrl != ''){
            $handle = fopen($this->CallUrl, "rb");
            $contents = '';
            while (!feof($handle)) {
            $contents .= fread($handle, 8192);
            }
            fclose($handle);

            $json = json_decode($contents, true);

            if($json['responseStatus'] == 200){ //If request was ok
                $this->TranslatedText = $json['responseData']['translatedText'];
                $this->DebugMsg = $json['responseDetails'];
                $this->DebugStatus = $json['responseStatus'];
                return $this->TranslatedText;
            } else { //Return some errors
                return false;
                $this->DebugMsg = $json['responseDetails'];
                $this->DebugStatus = $json['responseStatus'];
            }
        } else {
            return false;
        }
    }
}//END OF CLASS
?> 


