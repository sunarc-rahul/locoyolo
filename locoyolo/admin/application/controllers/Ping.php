<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Event (EventController)
 * User Class to control all user related operations.
 * @author : Rahul Gahlot
 * @version : 1.1
 * @since : 7 spt 2017
 */
class Ping extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ping_model');
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
        $this->global['pageTitle'] = 'Locoyolo : Ping';

       // $this->loadViews("templates/header");
        $this->loadViews("ping/list", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the user list
     */
    function pingListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('Ping_model');

            $searchText = $this->input->post('searchText')?$this->input->post('searchText'):'';
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->Ping_model->pingListingCount($searchText);
            $returns = $this->paginationCompress ( "ping/", $count, 5 );

            $data['pingRecords'] = $this->Ping_model->pingListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Locoyolo : Ping Listing';

            $this->loadViews("pings/list", $this->global, $data, NULL);
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
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deletePing()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $PingId = $this->input->post('id');

            $result = $this->Ping_model->deletePing($PingId);

            if ($result > 0) { echo 'Y'; }
            else { echo "N"; }
        }
    }

    function viewPing($pingId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            if($pingId == null)
            {
                redirect('pings');
            }
            $data['pingInfo'] = $this->Ping_model->getPingInfo($pingId);
            $this->global['pageTitle'] = 'Locoyolo : View Ping';
            $this->loadViews("Pings/view", $this->global, $data, NULL);
        }
    }
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }
}
?>