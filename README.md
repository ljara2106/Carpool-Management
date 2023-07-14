# CarPool Management

This is carpool management - a simple managment tool for schools to be able to monitor dismissal via QR code scanner.
It is supposed to scan the student ID "embedded" in the QR code that goes in the parent's car windshield.
The idea came to me as parent with privacy concern with the way many schools are currently handling carpool pickups lanes with plain text student's information, which 
includes, student first and last names, grade and teacher's name.
Now, all of the student's information gets displayed after scanning the unique student's ID and then pushed off into different location in MySQL database to be read.

Pros:
- Privacy for student's information.
- Students can stay in their classroom and teacher can see who of their students are on queue.
- No more walkie-talkies.
- No more paper tag printout (can use QR code on phone)
- reCAPTCHA implemented (keep mean bots away)

Cons:
- Internet required...


Technologies in this tool:

-PHP
-MySQL
-Javascript
-HTML




![ezgif com-gif-maker (1)](https://user-images.githubusercontent.com/20650464/209595806-8379244a-5913-49a9-9624-82f82b5b47fe.gif)



Big thanks to:
- https://blog.minhazav.dev/research/html5-qrcode (QR scanner tech)
- https://gist.github.com/kus/3f01d60569eeadefe3a1 (fix for sounds on iOS)



