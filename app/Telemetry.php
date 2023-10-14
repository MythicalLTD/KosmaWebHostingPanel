<?php 
namespace Kosma; 

class Telemetry {
    public static function NewUser() {
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.mythicalsystems.me/telemetry?project=kosmapanel&action=NewUser&authKey=AxWTnecj85SI4bG6rIP8bvw2uCF7W5MmkJcQIkrYS80MzeTraQWyICL690XOio8F",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
        ]);

        curl_exec($curl);
    }
    public static function NewWebsite() {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.mythicalsystems.me/telemetry?project=kosmapanel&action=NewWebsite&authKey=AxWTnecj85SI4bG6rIP8bvw2uCF7W5MmkJcQIkrYS80MzeTraQWyICL690XOio8F",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
        ]);
    
        curl_exec($curl);
    }
}
?>