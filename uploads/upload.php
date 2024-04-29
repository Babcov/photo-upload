<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
        $subject = 'New photo uploaded';
        $message = 'A new photo has been uploaded to your website.';
        $headers = 'From: your_email@example.com' . "\r\n";

        $attachmentPath = $uploadFile;
        $attachmentName = basename($attachmentPath);
        $attachmentData = chunk_split(base64_encode(file_get_contents($attachmentPath)));

        $eol = PHP_EOL;
        $boundaries = "--" . md5(uniqid(time()));
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundaries . "\"" . $eol . $eol;
        $body = "--" . $boundaries . $eol;
        $body .= "Content-Type: text/plain; charset=UTF-8" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol . $eol;
        $body .= chunk_split(base64_encode($message)) . $eol;
        $body .= "--" . $boundaries . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $attachmentName . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol . $eol;
        $body .= $attachmentData . $eol;
        $body .= "--" . $boundaries . "--" . $eol;

        if (mail($email, $subject, $body, $headers)) {
            echo "Photo uploaded and email sent successfully!";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Error uploading photo.";
    }
}
