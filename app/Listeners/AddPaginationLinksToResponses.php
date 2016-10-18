<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 25/07/2016
 * Time: 5:33 PM
 */

namespace App\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;

class AddPaginationLinksToResponses
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(ResponseWasMorphed $event)
    {
//        if (isset($event->content['meta']['pagination'])) {
//            $links = $event->content['meta']['pagination']['links'];
//
//            $event->response->headers->set(
//                'link',
//                sprintf('<%s>; rel="next", <%s>; rel="prev"', $links['links']['next'], $links['links']['previous'])
//            );
//        }

//        $event->content['time'] = 123;

//        die(print_r($event->response->getStatusCode()));
//        if(isset($event->))

//        print_r($event->response->getStatusCode());
        $temp_content = $event->content;
        if($event->response->getStatusCode() == 200){
            $event->content = array(
                'message' => 'success',
                'errors' => [],
                'status_code' => 200,
                'fail' => 0,
                'return' => $temp_content
            );
        } else{
            $event->content['fail'] = 1;
            $event->content['data'] = [];
        }
//        print_r($event->content);
//        echo PHP_EOL;
//        die(print_r($event->response));
    }
}