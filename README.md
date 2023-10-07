#MRZ Scanner

The MRZ Scanner is a PHP-based project that extracts Machine Readable Zone (MRZ) data from images using Tesseract OCR and the Rakibdevs\MrzParser library. This README provides instructions on setting up and running the project on an Apache server in a Windows environment.
Prerequisites

Before you can run the MRZ Scanner project, make sure you have the following prerequisites in place:

    PHP: Ensure you have PHP installed on your Windows server.

    Composer: Composer is required for managing PHP dependencies. You can download and install Composer from getcomposer.org.

    Tesseract OCR: While Tesseract OCR is not a PHP extension, you need to have it installed and accessible from your PHP environment. Here's how to set it up:
        Download the Windows installer for Tesseract OCR from the Tesseract GitHub releases page.
        Run the installer and follow the installation instructions.
        Make sure the Tesseract executable is in your system's PATH.

    GD Extension: The GD extension is required for image manipulation. Here's how to set it up:
        Open your php.ini file (located in your PHP installation directory).
        Uncomment the line that includes the GD extension by removing the semicolon at the beginning of the line. It should look like this:

        makefile

        extension=gd

        Save the php.ini file and restart Apache.

    thiagoalessio\TesseractOCR and Rakibdevs\MrzParser Libraries: These libraries are PHP dependencies that can be installed using Composer. You don't need to install them manually; Composer will handle it during the installation process.

Installation

Follow these steps to set up and run the MRZ Scanner project on your Windows server:

    Clone the repository to your server:

    bash

git clone https://github.com/Mmicx007/MRZ-Scanner.git

Change to the project directory:

bash

cd MRZ-Scanner

Install PHP dependencies using Composer:

bash

    composer install

    Configure Tesseract OCR:
        Ensure that Tesseract OCR is properly installed and its executable is accessible from your PHP environment.
        Update the $tesseract_data_path variable in your project to point to the directory containing Tesseract language data files if needed.

Usage

    Start your Apache server to make the project accessible over HTTP.

    Access the MRZ Scanner project through your web browser.

    Upload an image containing MRZ data to the project's web interface.

    The project will use Tesseract OCR to extract MRZ data from the image and display it on the web interface.

License

This project is licensed under the MIT License - see the LICENSE file for details.
Acknowledgments

    The MRZ Scanner project uses the Tesseract OCR for text recognition.
    MRZ data parsing is performed using the Rakibdevs\MrzParser library.

Contact

If you have any questions or need assistance, please contact [musadiq.cx@gmail.com].
