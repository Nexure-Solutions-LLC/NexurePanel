<?php

    if ($pagetitle == "Cali Mail") {

        // Check for subject thats non-standard encoding

        function decodeMimeStr($string, $charset = 'UTF-8') {

            if (preg_match_all('/=\?([^?]+)\?(B|Q)\?([^?]+)\?=/i', $string, $matches)) {

                $decoded_string = '';

                for ($i = 0; $i < count($matches[0]); $i++) {

                    $encoding = strtoupper($matches[2][$i]);
                    $data = $matches[3][$i];

                    switch ($encoding) {
                        case 'B':
                            $decoded_string .= base64_decode($data);
                            break;
                        case 'Q':
                            $decoded_string .= quoted_printable_decode(str_replace('_', ' ', $data));
                            break;
                    }

                }

                return mb_convert_encoding($decoded_string, $charset, $matches[1][0]);

            } else {

                return $string;
            }

        }

        // This formats the date to MM/DD/YYYY HH:MM AM/PM

        function formatDate($dateStr) {

            $date = DateTime::createFromFormat('D, d M Y H:i:s O', $dateStr);
            return $date ? $date->format('m/d/y h:i A') : 'Unknown date';

        }

        // Recursive function to get the message part
        
        function getMessagePart($structure, $partNumber, $inbox, $emailNumber) {
            
            $data = imap_fetchbody($inbox, $emailNumber, $partNumber);

            switch ($structure->encoding) {
                case 3: // BASE64
                    return base64_decode($data);
                case 4: // QUOTED-PRINTABLE
                    return quoted_printable_decode($data);
                default:
                    return $data;
            }

        }

        // Function to extract parts recursively

        function extractParts($structure, $partNumber, $inbox, $emailNumber, &$plainText, &$htmlText) {

            if ($structure->type == 0) { // TYPE: Text

                if ($structure->subtype == 'PLAIN') {

                    $plainText .= getMessagePart($structure, $partNumber, $inbox, $emailNumber);

                } elseif ($structure->subtype == 'HTML') {

                    $htmlText .= getMessagePart($structure, $partNumber, $inbox, $emailNumber);

                }

            } elseif ($structure->type == 1 && isset($structure->parts)) { // TYPE: Multipart

                foreach ($structure->parts as $partIndex => $subPart) {

                    extractParts($subPart, $partNumber . '.' . ($partIndex + 1), $inbox, $emailNumber, $plainText, $htmlText);
                    
                }

            }

        }
        

    } else {

        header("location:/error/genericSystemError");

    }

?>