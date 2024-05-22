<?php
function replaceUnicodeCharacter($str) {
    $unicodeCharacters = [
        "\u202f", "\U202F", "\u00e2", "\u20ac", "\u00af", 'â€¯'
    ];
    foreach ($unicodeCharacters as $unicode) {
        $str = str_replace($unicode, ' ', $str);
    }

    $str = str_replace('\"', '"', $str);

    return $str;
}

// Test the function
$input = "FW: GAP3841197 \/ 3 - Member wanting call back from MCA\nExternal\nInbox\n\nKatherine Lafleur\nMay 21, 2024, 11:30\u202fAM (19 hours ago)\nto me, Karen, Shirley\n\nDear Keasha,\n\nPlease contact the client and let me have feedback.\n\nKind Regards,\nKatherine\n\n";
$myarr=array("str"=>$input,"status"=>"date_entered");
$sendobj=json_encode($myarr);

$cleaned = replaceUnicodeCharacter($input);

echo "Original: " . $input . "\n"; // Show hidden characters in hex
echo "<hr>";
echo "Cleaned: $cleaned\n";

?>