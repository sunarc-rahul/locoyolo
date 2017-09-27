<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Event (EventController)
 * User Class to control all user related operations.
 * @author : Rahul Gahlot
 * @version : 1.1
 * @since : 7 spt 2017
 */
class Event extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Event_model');
        $this->isLoggedIn();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        $ci = get_instance();
        $ci->load->helper('ci');
        $this->load->helper('url');
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Locoyolo : Dashboard';

       // $this->loadViews("templates/header");
        $this->loadViews("event/list", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the user list
     */
    function eventListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('Event_model');

            $searchText = $this->input->post('searchText')?$this->input->post('searchText'):'';
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->Event_model->eventListingCount($searchText);
            $returns = $this->paginationCompress ( "events/", $count, 5 );

            $data['eventRecords'] = $this->Event_model->eventListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Locoyolo : Event Listing';

            $this->loadViews("events/list", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('User_model');
            $data['roles'] = $this->User_model->getUserRoles();

            $this->global['pageTitle'] = 'CodeInsect : Add New User';
            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");
        if(empty($userId)){
            $result = $this->User_model->checkEmailExists($email);
        } else {
            $result = $this->User_model->checkEmailExists($email, $userId);
        }
        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }

    /**
     * This function is used to add new user to the system
     */


    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function viewEvent($eventId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            if($eventId == null)
            {
                redirect('events');
            }
            $data['eventInfo'] = $this->Event_model->getEventInfo($eventId);
            $this->global['pageTitle'] = 'Locoyolo : View Event';
            $this->loadViews("events/view", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteEvent()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $eventId = $this->input->post('id');

            $result = $this->Event_model->deleteEvent($eventId);

            if ($result > 0) { echo 'Y'; }
            else { echo "N"; }
        }
    }

    /**
     * This function is used to load the change password screen
     */


    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}
?>