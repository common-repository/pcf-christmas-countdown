<?php

function pcfcc_calculate($day, $month, $year){
    
    date_default_timezone_set('Europe/London');
    
    $date = strtotime($day.'-'.$month.'-'.$year);
    
    $remaining = $date - time();
    $days = floor($remaining/86400);
    $days++;
    $weeks = floor($days/7);
    $day_remainder = (int)($days % 7);
    
    $sleeps = $days-1;
    
    return array($days, $weeks, $day_remainder, $sleeps);
}

function pcfcc_countdown($id, $type){
    $day = 25;
    $month = 12;
    $year = date("Y");
    
    list($days, $weeks, $day_remainder, $sleeps) = pcfcc_calculate($day, $month, $year);
    
    if($days <= 0){
        $year = date("Y", strtotime('+1 year'));
    }
    
    list($days, $weeks, $day_remainder, $sleeps) = pcfcc_calculate($day, $month, $year);
    
/****************************************************** Change Output ******************************************************/
    $daysop = "$days days";
   
    if($day_remainder != 0 && $weeks != 0){
        $weeksop = "$weeks weeks and $day_remainder days";
    }
    elseif($day_remainder == 0){
        $weeksop = "exactly $weeks weeks";
    }
    elseif($weeks == 0){
        $weeksop = "only $day_remainder days";
    }
    
    $sleepsop = "$sleeps sleeps";
    
    $event = "Christmas";

/****************************************************** Set Output ******************************************************/
    if($type == 'weeks'){
        if($day_remainder == 1 && $weeks == 1){
            $weeksop = str_replace('days', 'day', $weeksop);
            $weeksop = str_replace('weeks', 'week', $weeksop);
        }
        elseif($weeks == 1){
            $weeksop = str_replace('weeks', 'week', $weeksop);
        }
        elseif($days == 1){
            $weeksop = str_replace('days', 'day', $weeksop);
        }
        
        $output = $weeksop;
    }
    elseif($type == 'sleeps'){
        if($sleeps == 1){
            $sleepsop = str_replace('sleeps', 'sleep', $sleepsop);
        }
        $output = $sleepsop;
    }
    else{
        if($days == 1){
            $daysop = str_replace('days', 'day', $daysop);
        }
        $output = $daysop;
    }
    
/****************************************************** Output ******************************************************/
    if($id){
        if($days != 0){
            echo "<p id='$id'>It's $output until $event!</p>";
        }
        else{
            echo "<p id='$id'>It's $event!</p>";
        }
	}
    else{
        if($days != 0){
            echo "<p>It's $output until $event!</p>";
        }
        else{
            echo "<p>It's $event!</p>";
        }
    }
}

function pcfcc_shortcode($atts){
    ob_start();
    
    $atts = shortcode_atts(
        array(
            'id' => '',
            'type' => 'days'
        ),
    $atts);
    
    $id = $atts['id'];
    $type = $atts['type'];
    
    pcfcc_countdown($id, $type);
    
    return ob_get_clean();
}

add_shortcode('pcf_xmas_countdown', 'pcfcc_shortcode');

?>