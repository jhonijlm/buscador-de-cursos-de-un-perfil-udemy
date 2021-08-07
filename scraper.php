<?php
 header('Content-Type: application/json');

function curl_exe($urlUser){
    $curl = curl_init($urlUser);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    $result = json_decode($result);
    curl_close($curl);
    return isset($result->results) ? $result : null;
    // $result = $result->results;
}


$userId = $_GET["userId"] ?? null;

$pageSize = 999999999;
if($userId){
    $urlUser = "https://www.udemy.com/api-2.0/users/$userId/subscribed-profile-courses/?fields[course]=@default,avg_rating_recent,rating,bestseller_badge_content,badges,content_info,discount,is_recently_published,is_wishlisted,is_saved,num_published_lectures,num_reviews,num_subscribers,buyable_object_type,free_course_subscribe_url,is_in_user_subscription,headline,instructional_level,objectives_summary,content_length_practice_test_questions,content_info_short,num_published_practice_tests,published_time,is_user_subscribed,has_closed_caption,preview_url,context_info,caption_languages&page=1&page_size=$pageSize";        
    $resulArray = [];
    $result = curl_exe($urlUser);

    if($result){
        $resulArray = $result->results;

        while($result != null){
            $result = curl_exe($result->next);
            if($result){            
                $resulArray = array_merge($resulArray, $result->results);
            }
        }
    }

    echo json_encode(["data" => $resulArray, "code" => 200]);
}else{
    echo json_encode(["data" => null, "code" => 404]);
}





?>