First open web.php.
I have created all three links for each question.
Go to excelcontroller.
1.For 1 question explanatin: 
  1.First connect with third party api to fetch data.one page gives only 20 results.
  2.So as per question we need top 1000 movies so i loop 50 times to get 1000 movies.
  3.Then i craeted a csv file for and insert all data in this CSv file. 
  4.At last i downloaded te csv file.Location of file is storage/reports/file_name.csv.

2.question:
  1.First i downloaded file from the give link and store it in public/reports folder.
  2.Then i create a migration for customer table.
  3.Run Migration.
  4.Now fetch data from the downloaded file in an array.
  5.Now in Loop i insert data in Customer Table.
  
  3 Question:
   1.Create account on mailtrap.io
   2.Add mail class where all mail functionality written.(check app/mail/Genericmailable.php)
   3.Add job class 
   4.Pick Random movie from alredy downloaded csv files
   5.pick random customer from from customers table.
   6.create a very basic Html template where dynamic data is added
   7.Attach Qr code in this mail
   8.Send mail using Queues.
   
   I have added my email id on email.
   
   Please Run php artisan queue:run --queue=communication_testing
   
   
   This is all flow of the test i have given.
   
   please Share the feedback
