# ncore

This script logs in to the website "ncore.cc" and retrieves the HTML of the "hitnrun" page. It then converts the HTML to an array and generates an email body from the array. Finally, it sends an email with the body to the specified email address.

The script uses the cURL function to make HTTP requests and the html_to_array and torrent_array_to_email_body functions to convert the HTML and generate the email body. The NAME and PASSWORD constants in the script contain the login credentials for the website.

The html_to_array function uses the DOMDocument class to parse the HTML and extract specific elements using an XPath query. The torrent_array_to_email_body function then processes the array and generates the email body as a string.