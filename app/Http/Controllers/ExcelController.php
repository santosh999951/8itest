<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Queue;
use App\Mail\GenericMailable;
use Mail;

class ExcelController extends Controller
{


	/**
     * Connect with the moviedb.org db and fetch data and insert data to csv file.
     *
     */
    public function getExcel() {

    	$popular_movies = [];

    	//Connect third part api using GuzzleHttp.
    	$client = new \GuzzleHttp\Client();

        //Fetch top 1000 movies data from the movie db.So loop 50 timess becoz 1 page result giving 20 result.so loop 50 times for getting 1000 movies..
    	for($i=1;$i<=50;$i++) {

    		//Passing $i for page number.
    		$request = $client->get('https://api.themoviedb.org/3/movie/popular?api_key=525cb8ce745e5ce7a4904b6eed337ad9&language=en-US&page='.$i);
            $response = json_decode($request->getBody()->getContents(),true);
            $popular_movies = array_merge($popular_movies, $response['results']);
            
     	}


        // Generate Excel file in storage/reports folder with current timestamp name.
    	$file = time().'.csv';

    	$file_path = storage_path() . '/' . 'reports/' . $file;

        $fp = fopen($file_path, 'w+');
        $header= array('popularity','title','poster_path');

        fputcsv($fp,$header);

        foreach ($popular_movies as $fields) {  
        	//You can add more coloums in Csv.For ease i have added only 3 coloums.
            fputcsv($fp, array($fields['popularity'],$fields['title'],$fields['poster_path']));	
        }

        fclose($fp);

        //Download Excel file.
        return response()->download($file_path);      
    }


    /**
     * Import data In database.Table name is customers.
     * Migration is in database folder.
     *
     */
    public function importcsv() {

        //Download file from the given url and store it in storage/reports folder.
    	$file_path = public_path() . '/' . 'reports/50-contacts.csv' ;

        
        $file = fopen($file_path,"r");

        while(!feof($file)){
         $row[] =fgetcsv($file);
        }

       
        //Remove last blank row.
        unset($row[51]);

        foreach ($row as $key => $value) { 
            $inserted_data=array(
            	'first_name'=>$value[0],
            	'last_name'=>$value[1],
            	'company_name'=>$value[2],
            	'address'=>$value[3],
            	'city'=>$value[4],
            	'country'=>$value[5],
            	'state'=>$value[6],
            	'zip'=>$value[7],
            	'phone1'=>$value[8],
            	'phone'=>$value[9],
            	'email'=>$value[10],
            );
            
            //Save values in model.
           Customer::create($inserted_data);
         }

         echo 'Importing Finish.Please check in your DB';

    }



     /**
     * Generate QR code and attach that QR code to mail.
     * Mail are sending through jobs and Queues.Default Queue driver is databse.
     * Check Job in jobs folder.
     * Mailtrap using for sending email
     */
    public function sendEmail() {

    	//Generate a Qr code with third party library.
    	$image=  \QrCode::size(500)->format('png')->generate('google.com', public_path('images/qrcode.png'));


        //Pick Random Customer from DB.
    	$customer = Customer::all()->random()->toArray();
    	$customer_name = $customer['first_name'].$customer['last_name'];


    	//Pick Random movie from generated csv file.

    	$file_path = storage_path() . '/' . 'reports/1595422852.csv' ;

    	$rows = file($file_path);
		$len = count($rows);

		$rand = [];
	    $r = rand(0, $len);
	    if (!in_array($r, $rand)) {
	        $rand[] = $r;
	    }

	    $csv = $rows[$r];
	    $data = str_getcsv($csv);
	    $movie_name = $data[1];

    	
        //Prepare a array where deafult email is my cuurent email and attach Qr code.
        $mail = [
            'to_email'  => 'singhalsantosh9@gmail.com',
            'subject'   => 'Movie Ticket',
            'view'      => 'test.movie',
            'view_data' => [
            	'name'=> $customer_name,
            	'movie_name' => $movie_name
                ],
            'attachment' => [
                'url'  => public_path('images/qrcode.png'),
                'name' => 'QR code',
            ],
        ];
        

       $this->send($mail);

       echo 'email send with Qr code and movie details';die;
    }


     /**
     * Sending Email.
     */
     private function send(array $mail) {

        // Mailable job.
        $job = new SendEmail($mail);
        Queue::pushOn('communication_testing', $job);

    }//end send()
}
