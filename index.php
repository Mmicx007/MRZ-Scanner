<?php
require_once __DIR__.'/vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

use Rakibdevs\MrzParser\MrzParser;


function getMRZString($image_path) {
    // Load the image using the GD library
    $image = imagecreatefromjpeg($image_path);

    // Convert the image to grayscale to improve OCR accuracy
    imagefilter($image, IMG_FILTER_GRAYSCALE);

    // Save the grayscale image to a temporary file
    $temp_filename = tempnam(sys_get_temp_dir(), "ocr-");
    imagejpeg($image, $temp_filename);

    // Use Tesseract to recognize the text in the image
    $ocr_text = shell_exec("tesseract $temp_filename stdout");


    // Remove newlines and whitespace from the OCR output
    $ocr_text = str_replace(array("\r", " "), "", $ocr_text);


    $pattern = '/^([A-Z0-9<]{44})$/m';
    // Extract the MRZ lines using a regular expression
    preg_match_all($pattern, $ocr_text, $matches);

    // Remove duplicates from the resulting array
    $matches = array_unique($matches[0]);

    $mrz_string = implode(PHP_EOL, $matches);

    unlink($temp_filename);

    return $mrz_string;
}

function is_base64_image_string_valid($base64_image_string) {
    
    // decode the base64 string into binary data
    $binary_data = base64_decode($base64_image_string, true);
    if (!$binary_data) {
        // input is not a valid base64 string
        return false;
    }

    // check if the binary data is a valid image
    $image_info = getimagesizefromstring($binary_data);
    if (!$image_info) {
        // binary data is not a valid image
        return false;
    }
    // binary data represents a valid image
    return true;
}

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method

// Set the default response code and message
$response_code = 200;
$response_message = "Error: Something went wrong";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['request_id']) && isset($_POST['image']))
    {
        if (!empty($_POST['request_id']) && !empty($_POST['image']))
        {
            $request_id  = $_POST['request_id'];
            $base64image = $_POST['image'];
            try
            {
                if (is_base64_image_string_valid($base64image))
                {
                    // Decode the image from base64 and save it to a file
                    $image_data = base64_decode($base64image);
                    $image_path = __DIR__.'/image/' . $request_id . '.jpg';
                    file_put_contents($image_path, $image_data);

                    $mrz_string = getMRZString($image_path);
                    $data = MrzParser::parse($mrz_string);
                    $data['request_id'] = $request_id;
                    $response_data = $data;
                    $response_code = 200;
                    $response_message = "MRZ Passwort Parsed successfully";
                }
                else
                {
                    $response_code = 400;
                    $response_message = "Image is corrupt or invalid";
                    $response_data = null;
                }
            }
            catch(Exception $e)
            {
                $response_code = 400;
                $response_message = $e->getMessage();
                $response_data = null;
            }
        }
        else
        {
            $response_code = 400;
            $response_message = "Params cannot be empty";
            $response_data = null;
        }
    }
    else
    {
        $response_code = 400;
        $response_message = "Missing Params";
        $response_data = null;
    }
} else {
    // Throw an exception if it's not a POST request
    $response_code = 405;
    $response_message = "Request Method Not Allowed";
    $response_data = null;
}


// Set the response status code
http_response_code($response_code);

// Create the response object
$response = array(
    'status'  => $response_code,
    'message' => $response_message,
    'data'    => $response_data
);

// Return the response as JSON
echo json_encode($response);