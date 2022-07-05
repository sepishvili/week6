<?php
/**
 * @file
 * Contains \Drupal\jobmodule\Controller\JobController.
 */
namespace Drupal\jobmodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;

/**
 * An example controller.
 */
class JobController extends ControllerBase {

  public function content() {
    $job_name = \Drupal::request()->request->get('title');
    $location = \Drupal::request()->request->get('location');
    $time= \Drupal::request()->request->get('box');

   
    

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    if(!$job_name && !$location && !$time) {
      $nids = $node_storage->getQuery()
          ->condition('type', 'job')
          ->execute();
  } else if($job_name&& $location){
    $nids = $node_storage->getQuery()
      ->condition('type', 'job')
      ->condition('field_country.value', $location)
      ->condition('field_job_name.value', $job_name)
      ->execute();
  } else if(!$location && !$time){
      $nids = $node_storage->getQuery()
          ->condition('type', 'job')
          ->condition('field_job_name.value', $job_name)
          ->execute();
  } else if(!$job_name && !$time){
      $nids = $node_storage->getQuery()
        ->condition('type', 'job')
        ->condition('field_country.value', $location)
        ->execute();
  } else if($location && $time){
      $nids = $node_storage->getQuery()
        ->condition('type', 'job')
        ->condition('field_duration.value', $time)
        ->condition('field_country.value', $location)
        ->execute();
  }  else if($job_name && $time){
    $nids = $node_storage->getQuery()
      ->condition('type', 'job')
      ->condition('field_duration.value', $time)
      ->condition('field_job_name.value', $job_name)
      ->execute();
  }  else if($job_name && $location && $time){
      $nids = $node_storage->getQuery()
        ->condition('type', 'job')
        ->condition('field_country.value', $location)
        ->condition('field_job_name.value', $job_name)
        ->condition('field_duration.value', $time)
        ->execute();
  }  else if($time){
      $nids = $node_storage->getQuery()
        ->condition('type', 'job')
        ->condition('field_duration.value', $time)
        ->execute();
  } 

    

    
    $results= Node::loadMultiple($nids);

    $jobs=[];

    foreach($results as $result){

        $fid = $result->field_img->getValue()[0]['target_id'];
        $file = File::load($fid);
        // Get origin image URI.
        $image_uri = $file->getFileUri();
        // Load image style "thumbnail".
        $style = ImageStyle::load('thumbnail');
        $uri = $style->buildUri($image_uri);
        // Get URL.
        $url = $style->buildUrl($image_uri);
        $thumbnail = $url;
        
       

        $title=$result->field_company_name->value;
        $time=$result->field_date->value;
        $job_name=$result->field_job_name->value;
        $duration=$result->field_duration->value;
        $country=$result->field_country->value;
        $nid = $result->nid->value;

        $jobs[]=[
            'title'=>$title,
            'time'=>$time,
            'job_name'=>$job_name,
            'duration'=>$duration,
            'country'=>$country,
            'thumbnail'=>$thumbnail,
            'nid'=>$nid
        ];
        
    }
   
    
    
    



    return [
      // Your theme hook name.
      '#theme' => 'jobmodule_theme_hook',
      // Your variables.
      '#jobs' => $jobs,
    //   '#title'=>$title,
    ];
  }
}