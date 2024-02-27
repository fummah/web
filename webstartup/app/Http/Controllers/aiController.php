<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Orhanerday\OpenAi\OpenAi;
use App\Models\CampaignModel;
use Redirect;
use Response;

class aiController extends Controller
{
    private $facebook_post_id;
    private function getFacebookKey()
    {
        return "EAAMD2J0SK6wBO1sovFd8KBQ50WCUlAs4sTKSNw9mcQhsGWGdEnI81vqXxUKS94gZC9n1gZAoh8O1IQwetuClzhZByfjY1YnrPVC83WOpd4Lt5GveexdaCUS6NjJRLteI8DxRv9CirzJcH677lLHZCIBRFBXy8p49KMd7i7ZBZAbgIZAkthpOxufSXo9FAZArI7LrkmGUm4KhcnEZD";
    }
      function query_ai(Request $request)    
    {
        $prompt=$request->prompt_name;
        $val=$request->val;        
        $open_ai_key = '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';
        $prompt=$request->prompt_name;       
        if($val=="0")
        {
            return $this->generateText($prompt,$open_ai_key);
        }
        else
        {
            return $this->generate_image($prompt,$open_ai_key);
        }
    }
    function generateText($prompt,$open_key)
    {
         
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n    \"model\": \"gpt-3.5-turbo-instruct\",\n    \"prompt\": \"Writing 3 Marketing Facebook caption for $prompt\",\n    \"max_tokens\": 150,\n    \"temperature\": 0.9\n  }");
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer '.$open_key;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    return 'Error:' . curl_error($ch);
}
curl_close($ch);
$chat=str_replace('\n', '<br>', $result);
return $chat;

    }

    function generate_image($prompt,$open_ai)
    {   
      $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/images/generations');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n    \"model\": \"dall-e-3\",\n    \"prompt\": \"Generate image for $prompt\",\n    \"n\": 1,\n    \"response_format\": \"url\",\n    \"size\": \"1024x1024\"\n  }");

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer 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';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    return 'Error:' . curl_error($ch);
}
curl_close($ch);
   return $result;
    }

    function add_socialpost(Request $request)
    {
    $description=$request->api_text; 
    $des_url=urlencode($description);
    $tocken=$this->getFacebookKey();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v17.0/128882556968314/feed?message='.$des_url.'&access_token='.$tocken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$result = curl_exec($ch);
if (curl_errno($ch)) {    
    $error=curl_error($ch);
    curl_close($ch);
    return Redirect::back()->withErrors(['msg' => 'Failed, Error : '. $error]);

   }
else
{
    if(strpos($result,'DOCTYPE')>-1)
    {
        return Redirect::back()->withErrors(['msg' => 'There is an error connecting to Facebook']);
    }
     $user_id=auth()->user()->id;
        $obj=new CampaignModel();
        $obj->campaign_type="Social Post";
        $obj->campaign_name=$request->campaign_name;
        $obj->campaign_description=$description;  
        $obj->post_id=$result;  
        $obj->entered_by=$user_id;
        $obj->user_id=$user_id;
        $add=$obj->save();
        if($add>0)
        {
            return Redirect::back()->with('success','New Post Successfully Created');
        }
        else
        {
            return Redirect::back()->withErrors(['msg' => 'Failed to create Post']);
        }
}
curl_close($ch);
    }
function facebook_analysis(Request $request)
{
    $campaign_id=(int)$request->campaign_id;
    $obj=CampaignModel::find($campaign_id);
    $post_content=$obj->post_id;
    if(strpos($post_content,'"id":')>-1)
    {
        $conobj=json_decode($post_content);
        $post_id=$conobj->id;
       
        $this->facebook_post_id=$post_id;
        $comments=$this->getComments();
        $shares=$this->getShares();
        $likes=$this->getLikes();
        $engagements=$this->getEngements();
        $anylitics_arr=array("comments"=>$comments,"shares"=>$shares,"likes"=>$likes,"engagements"=>$engagements);
        
        return $anylitics_arr; 
    }
    else
    {
        return []; 
    }


}
    private function getFBDetails($part_url="comments?summary=1")
    {
        $ch = curl_init();
        $token=$this->getFacebookKey();
        $post_id=$this->facebook_post_id;
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v17.0/'.$post_id.'/'.$part_url.'&access_token='.$token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);
return $response;
    }
    private function getComments()
    {
        $response=$this->getFBDetails();
        $deco=json_decode($response,true);
        return count($deco["data"]);
    }
      private function getShares()
    {
        $part_url="?fields=shares";
        $response=$this->getFBDetails($part_url);
        $deco=json_decode($response,true);
        return isset($deco["shares"]["count"])?($deco["shares"]["count"]):0;
    }
      private function getLikes()
    {
        $part_url="likes?summary=1";
        $response=$this->getFBDetails($part_url);
        $deco=json_decode($response,true);
        return isset($deco["summary"]["total_count"])?($deco["summary"]["total_count"]):0;
    }
       private function getEngements()
    {
        $part_url="insights?metric=post_engaged_users";
        $response=$this->getFBDetails($part_url);
        $deco=json_decode($response,true);
        return count($deco["data"]);
    }
    function seo(Request $request)
    {
        if(!isset($request->search_url))
        {
            $search_url="";
            $obj=[];
        }
        else
        {
        $search_url=$request->search_url;
        $curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.seobility.net/en/resellerapi/seocheck?apikey=8c576c79d46b5daa5ab65e0bd52e86ed8e270c67&url=".$search_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYPEER=> false,
  CURLOPT_HTTPHEADER => [
    "X-RapidAPI-Host: zenserp.p.rapidapi.com",
    "X-RapidAPI-Key: 7f324798dbmsh34750733e28474dp13f7ccjsna3946dcdd551"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

}
$obj=json_decode($response,true);
}
       return view("pages.seo",compact(['obj','search_url']));
    }

    function content_management()
    {
       return view("pages.content-management"); 
    }
    function image_editor()
    {
       return view("pages.image_editor"); 
    }
    function keyword_suggestion(Request $request)
    {
        $keyword="";
        $countries=$this->countryList();
if(isset($request->keyword))
{
    $keyword=$request->keyword; 
    $location=$request->location; 
    $urldesc=urlencode($keyword);
        $curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://seo-keyword-research.p.rapidapi.com/keynew.php?keyword=".$urldesc."&country=".$location,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_SSL_VERIFYPEER=> false,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "X-RapidAPI-Host: seo-keyword-research.p.rapidapi.com",
    "X-RapidAPI-Key: 7f324798dbmsh34750733e28474dp13f7ccjsna3946dcdd551"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$response=json_decode($response,true);
if ($err) {
  $response = ["cURL Error #:" . $err];
} 
}
else
{
    $response =[];
}
//print_r($response);
       return view("pages.keyword_suggestion",compact(['keyword','countries','response'])); 
    }
    private function countryList()
    {
        $country='[ 
  {"name": "Afghanistan", "code": "AF"}, 
  {"name": "Åland Islands", "code": "AX"}, 
  {"name": "Albania", "code": "AL"}, 
  {"name": "Algeria", "code": "DZ"}, 
  {"name": "American Samoa", "code": "AS"}, 
  {"name": "AndorrA", "code": "AD"}, 
  {"name": "Angola", "code": "AO"}, 
  {"name": "Anguilla", "code": "AI"}, 
  {"name": "Antarctica", "code": "AQ"}, 
  {"name": "Antigua and Barbuda", "code": "AG"}, 
  {"name": "Argentina", "code": "AR"}, 
  {"name": "Armenia", "code": "AM"}, 
  {"name": "Aruba", "code": "AW"}, 
  {"name": "Australia", "code": "AU"}, 
  {"name": "Austria", "code": "AT"}, 
  {"name": "Azerbaijan", "code": "AZ"}, 
  {"name": "Bahamas", "code": "BS"}, 
  {"name": "Bahrain", "code": "BH"}, 
  {"name": "Bangladesh", "code": "BD"}, 
  {"name": "Barbados", "code": "BB"}, 
  {"name": "Belarus", "code": "BY"}, 
  {"name": "Belgium", "code": "BE"}, 
  {"name": "Belize", "code": "BZ"}, 
  {"name": "Benin", "code": "BJ"}, 
  {"name": "Bermuda", "code": "BM"}, 
  {"name": "Bhutan", "code": "BT"}, 
  {"name": "Bolivia", "code": "BO"}, 
  {"name": "Bosnia and Herzegovina", "code": "BA"}, 
  {"name": "Botswana", "code": "BW"}, 
  {"name": "Bouvet Island", "code": "BV"}, 
  {"name": "Brazil", "code": "BR"}, 
  {"name": "British Indian Ocean Territory", "code": "IO"}, 
  {"name": "Brunei Darussalam", "code": "BN"}, 
  {"name": "Bulgaria", "code": "BG"}, 
  {"name": "Burkina Faso", "code": "BF"}, 
  {"name": "Burundi", "code": "BI"}, 
  {"name": "Cambodia", "code": "KH"}, 
  {"name": "Cameroon", "code": "CM"}, 
  {"name": "Canada", "code": "CA"}, 
  {"name": "Cape Verde", "code": "CV"}, 
  {"name": "Cayman Islands", "code": "KY"}, 
  {"name": "Central African Republic", "code": "CF"}, 
  {"name": "Chad", "code": "TD"}, 
  {"name": "Chile", "code": "CL"}, 
  {"name": "China", "code": "CN"}, 
  {"name": "Christmas Island", "code": "CX"}, 
  {"name": "Cocos (Keeling) Islands", "code": "CC"}, 
  {"name": "Colombia", "code": "CO"}, 
  {"name": "Comoros", "code": "KM"}, 
  {"name": "Congo", "code": "CG"}, 
  {"name": "Congo, The Democratic Republic of the Congo", "code": "CD"}, 
  {"name": "Cook Islands", "code": "CK"}, 
  {"name": "Costa Rica", "code": "CR"}, 
  {"name": "Cote DIvoire", "code": "CI"}, 
  {"name": "Croatia", "code": "HR"}, 
  {"name": "Cuba", "code": "CU"}, 
  {"name": "Cyprus", "code": "CY"}, 
  {"name": "Czech Republic", "code": "CZ"}, 
  {"name": "Denmark", "code": "DK"}, 
  {"name": "Djibouti", "code": "DJ"}, 
  {"name": "Dominica", "code": "DM"}, 
  {"name": "Dominican Republic", "code": "DO"}, 
  {"name": "Ecuador", "code": "EC"}, 
  {"name": "Egypt", "code": "EG"}, 
  {"name": "El Salvador", "code": "SV"}, 
  {"name": "Equatorial Guinea", "code": "GQ"}, 
  {"name": "Eritrea", "code": "ER"}, 
  {"name": "Estonia", "code": "EE"}, 
  {"name": "Ethiopia", "code": "ET"}, 
  {"name": "Falkland Islands (Malvinas)", "code": "FK"}, 
  {"name": "Faroe Islands", "code": "FO"}, 
  {"name": "Fiji", "code": "FJ"}, 
  {"name": "Finland", "code": "FI"}, 
  {"name": "France", "code": "FR"}, 
  {"name": "French Guiana", "code": "GF"}, 
  {"name": "French Polynesia", "code": "PF"}, 
  {"name": "French Southern Territories", "code": "TF"}, 
  {"name": "Gabon", "code": "GA"}, 
  {"name": "Gambia", "code": "GM"}, 
  {"name": "Georgia", "code": "GE"}, 
  {"name": "Germany", "code": "DE"}, 
  {"name": "Ghana", "code": "GH"}, 
  {"name": "Gibraltar", "code": "GI"}, 
  {"name": "Greece", "code": "GR"}, 
  {"name": "Greenland", "code": "GL"}, 
  {"name": "Grenada", "code": "GD"}, 
  {"name": "Guadeloupe", "code": "GP"}, 
  {"name": "Guam", "code": "GU"}, 
  {"name": "Guatemala", "code": "GT"}, 
  {"name": "Guernsey", "code": "GG"}, 
  {"name": "Guinea", "code": "GN"}, 
  {"name": "Guinea-Bissau", "code": "GW"}, 
  {"name": "Guyana", "code": "GY"}, 
  {"name": "Haiti", "code": "HT"}, 
  {"name": "Heard Island and Mcdonald Islands", "code": "HM"}, 
  {"name": "Holy See (Vatican City State)", "code": "VA"}, 
  {"name": "Honduras", "code": "HN"}, 
  {"name": "Hong Kong", "code": "HK"}, 
  {"name": "Hungary", "code": "HU"}, 
  {"name": "Iceland", "code": "IS"}, 
  {"name": "India", "code": "IN"}, 
  {"name": "Indonesia", "code": "ID"}, 
  {"name": "Iran, Islamic Republic Of", "code": "IR"}, 
  {"name": "Iraq", "code": "IQ"}, 
  {"name": "Ireland", "code": "IE"}, 
  {"name": "Isle of Man", "code": "IM"}, 
  {"name": "Israel", "code": "IL"}, 
  {"name": "Italy", "code": "IT"}, 
  {"name": "Jamaica", "code": "JM"}, 
  {"name": "Japan", "code": "JP"}, 
  {"name": "Jersey", "code": "JE"}, 
  {"name": "Jordan", "code": "JO"}, 
  {"name": "Kazakhstan", "code": "KZ"}, 
  {"name": "Kenya", "code": "KE"}, 
  {"name": "Kiribati", "code": "KI"}, 
  {"name": "Korea, Democratic People", "code": "KP"}, 
  {"name": "Korea, Republic of", "code": "KR"}, 
  {"name": "Kuwait", "code": "KW"}, 
  {"name": "Kyrgyzstan", "code": "KG"}, 
  {"name": "Lao People Democratic Republic", "code": "LA"}, 
  {"name": "Latvia", "code": "LV"}, 
  {"name": "Lebanon", "code": "LB"}, 
  {"name": "Lesotho", "code": "LS"}, 
  {"name": "Liberia", "code": "LR"}, 
  {"name": "Libyan Arab Jamahiriya", "code": "LY"}, 
  {"name": "Liechtenstein", "code": "LI"}, 
  {"name": "Lithuania", "code": "LT"}, 
  {"name": "Luxembourg", "code": "LU"}, 
  {"name": "Macao", "code": "MO"}, 
  {"name": "Macedonia, The Former Yugoslav Republic of", "code": "MK"}, 
  {"name": "Madagascar", "code": "MG"}, 
  {"name": "Malawi", "code": "MW"}, 
  {"name": "Malaysia", "code": "MY"}, 
  {"name": "Maldives", "code": "MV"}, 
  {"name": "Mali", "code": "ML"}, 
  {"name": "Malta", "code": "MT"}, 
  {"name": "Marshall Islands", "code": "MH"}, 
  {"name": "Martinique", "code": "MQ"}, 
  {"name": "Mauritania", "code": "MR"}, 
  {"name": "Mauritius", "code": "MU"}, 
  {"name": "Mayotte", "code": "YT"}, 
  {"name": "Mexico", "code": "MX"}, 
  {"name": "Micronesia, Federated States of", "code": "FM"}, 
  {"name": "Moldova, Republic of", "code": "MD"}, 
  {"name": "Monaco", "code": "MC"}, 
  {"name": "Mongolia", "code": "MN"}, 
  {"name": "Montserrat", "code": "MS"}, 
  {"name": "Morocco", "code": "MA"}, 
  {"name": "Mozambique", "code": "MZ"}, 
  {"name": "Myanmar", "code": "MM"}, 
  {"name": "Namibia", "code": "NA"}, 
  {"name": "Nauru", "code": "NR"}, 
  {"name": "Nepal", "code": "NP"}, 
  {"name": "Netherlands", "code": "NL"}, 
  {"name": "Netherlands Antilles", "code": "AN"}, 
  {"name": "New Caledonia", "code": "NC"}, 
  {"name": "New Zealand", "code": "NZ"}, 
  {"name": "Nicaragua", "code": "NI"}, 
  {"name": "Niger", "code": "NE"}, 
  {"name": "Nigeria", "code": "NG"}, 
  {"name": "Niue", "code": "NU"}, 
  {"name": "Norfolk Island", "code": "NF"}, 
  {"name": "Northern Mariana Islands", "code": "MP"}, 
  {"name": "Norway", "code": "NO"}, 
  {"name": "Oman", "code": "OM"}, 
  {"name": "Pakistan", "code": "PK"}, 
  {"name": "Palau", "code": "PW"}, 
  {"name": "Palestinian Territory, Occupied", "code": "PS"}, 
  {"name": "Panama", "code": "PA"}, 
  {"name": "Papua New Guinea", "code": "PG"}, 
  {"name": "Paraguay", "code": "PY"}, 
  {"name": "Peru", "code": "PE"}, 
  {"name": "Philippines", "code": "PH"}, 
  {"name": "Pitcairn", "code": "PN"}, 
  {"name": "Poland", "code": "PL"}, 
  {"name": "Portugal", "code": "PT"}, 
  {"name": "Puerto Rico", "code": "PR"}, 
  {"name": "Qatar", "code": "QA"}, 
  {"name": "Reunion", "code": "RE"}, 
  {"name": "Romania", "code": "RO"}, 
  {"name": "Russian Federation", "code": "RU"}, 
  {"name": "RWANDA", "code": "RW"}, 
  {"name": "Saint Helena", "code": "SH"}, 
  {"name": "Saint Kitts and Nevis", "code": "KN"}, 
  {"name": "Saint Lucia", "code": "LC"}, 
  {"name": "Saint Pierre and Miquelon", "code": "PM"}, 
  {"name": "Saint Vincent and the Grenadines", "code": "VC"}, 
  {"name": "Samoa", "code": "WS"}, 
  {"name": "San Marino", "code": "SM"}, 
  {"name": "Sao Tome and Principe", "code": "ST"}, 
  {"name": "Saudi Arabia", "code": "SA"}, 
  {"name": "Senegal", "code": "SN"}, 
  {"name": "Serbia and Montenegro", "code": "CS"}, 
  {"name": "Seychelles", "code": "SC"}, 
  {"name": "Sierra Leone", "code": "SL"}, 
  {"name": "Singapore", "code": "SG"}, 
  {"name": "Slovakia", "code": "SK"}, 
  {"name": "Slovenia", "code": "SI"}, 
  {"name": "Solomon Islands", "code": "SB"}, 
  {"name": "Somalia", "code": "SO"}, 
  {"name": "South Africa", "code": "ZA"}, 
  {"name": "South Georgia and the South Sandwich Islands", "code": "GS"}, 
  {"name": "Spain", "code": "ES"}, 
  {"name": "Sri Lanka", "code": "LK"}, 
  {"name": "Sudan", "code": "SD"}, 
  {"name": "Suri", "code": "SR"}, 
  {"name": "Svalbard and Jan Mayen", "code": "SJ"}, 
  {"name": "Swaziland", "code": "SZ"}, 
  {"name": "Sweden", "code": "SE"}, 
  {"name": "Switzerland", "code": "CH"}, 
  {"name": "Syrian Arab Republic", "code": "SY"}, 
  {"name": "Taiwan, Province of China", "code": "TW"}, 
  {"name": "Tajikistan", "code": "TJ"}, 
  {"name": "Tanzania, United Republic of", "code": "TZ"}, 
  {"name": "Thailand", "code": "TH"}, 
  {"name": "Timor-Leste", "code": "TL"}, 
  {"name": "Togo", "code": "TG"}, 
  {"name": "Tokelau", "code": "TK"}, 
  {"name": "Tonga", "code": "TO"}, 
  {"name": "Trinidad and Tobago", "code": "TT"}, 
  {"name": "Tunisia", "code": "TN"}, 
  {"name": "Turkey", "code": "TR"}, 
  {"name": "Turkmenistan", "code": "TM"}, 
  {"name": "Turks and Caicos Islands", "code": "TC"}, 
  {"name": "Tuvalu", "code": "TV"}, 
  {"name": "Uganda", "code": "UG"}, 
  {"name": "Ukraine", "code": "UA"}, 
  {"name": "United Arab Emirates", "code": "AE"}, 
  {"name": "United Kingdom", "code": "GB"}, 
  {"name": "United States", "code": "US"}, 
  {"name": "United States Minor Outlying Islands", "code": "UM"}, 
  {"name": "Uruguay", "code": "UY"}, 
  {"name": "Uzbekistan", "code": "UZ"}, 
  {"name": "Vanuatu", "code": "VU"}, 
  {"name": "Venezuela", "code": "VE"}, 
  {"name": "Viet Nam", "code": "VN"}, 
  {"name": "Virgin Islands, British", "code": "VG"}, 
  {"name": "Virgin Islands, U.S.", "code": "VI"}, 
  {"name": "Wallis and Futuna", "code": "WF"}, 
  {"name": "Western Sahara", "code": "EH"}, 
  {"name": "Yemen", "code": "YE"}, 
  {"name": "Zambia", "code": "ZM"}, 
  {"name": "Zimbabwe", "code": "ZW"} 
]';
return json_decode($country,true);
    }
}


//oauth/access_token?grant_type=fb_exchange_token&client_id=848653813164972&client_secret=30d7e443e23b1782ccdf96715d5aa61e&fb_exchange_token=EAAMD2J0SK6wBO7lH6128kTz3oilUElol9j1hWTVYmQvPQZAN2JbuZCgMrnUQHD7fVAuFen6gYcgUGTuaTr0HzGszuftHfT5LDyxXD1ZBdN2ptgLxxhjQTZAylqKBoGgKqFW7Wiy7lQmaGlNkyBAsVhErdyJQtDyXOaLVRtTf5NwXhSWeDx5TdnxyCFcftja2ScQsDyX1PZAoTacArZBSVzSQmPPT6YAZBUZD