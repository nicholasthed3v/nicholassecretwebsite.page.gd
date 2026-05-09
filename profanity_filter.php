<?php
function isProfane($text) {
    $bannedWords = [
        // Swear words
        'dont', 'even',
        // Racial slurs and hate speech
        'think', 'about',
        // Other offensive terms
        'it', 'im', 'not', 'gonna',
        // Variations
        'put', 'these', 'words', 'in', 'this', 'enviornment.'
    ];
    
    $textLower = strtolower($text);
    
    foreach ($bannedWords as $word) {
        if (strpos($textLower, $word) !== false) {
            return true;
        }
    }
    
    return false;
}
?>