# CarPool Management

This is carpool management - a simple management tool for elementary schools to be able to monitor dismissal via a QR code scanner. It allows school staff to scan or type in the student ID "embedded" in the QR code that goes on the parent's car windshield and automatically sends that data to the teacher's monitoring view, where they can see which of their students were scanned and proceed to send them out for pickup. The idea came to me as a parent with privacy and safety concerns about the way many schools are currently handling carpool pickup lanes with plain text student information, which includes student first and last names, grade, and teacher's name. Now, all of the student's information is displayed after scanning the unique student ID and then pushed into a different location in the MySQL database to be read.

Pros:
- Privacy for student's information.
- Students can stay in their classroom and teacher can see who of their students are in queue.
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



