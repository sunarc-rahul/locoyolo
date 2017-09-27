<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Rahul Gahlot
 * @since : 09 September 2017
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->isLoggedIn();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        $ci = get_instance();
        $ci->load->helper('ci');


    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Locoyolo : Dashboard';
        $this->load->model('Event_model');
        $this->load->model('Ping_model');
        $this->global['events'] =  $this->Event_model->eventListingCount();
        $this->global['pings'] =  $this->Ping_model->pingListingCount();
        $this->global['users'] =  $this->User_model->userListingCount();



       // $this->loadViews("templates/header");
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the user list
     */
    function userListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('User_model');

            $searchText = $this->input->post('searchText')?$this->input->post('searchText'):'';
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->User_model->userListingCount($searchText);
            $returns = $this->paginationCompress ( "users/", $count, 5 );

            $data['userRecords'] = $this->User_model->userListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'CodeInsect : User Listing';
            $data['page']=$returns["segment"];
            $this->loadViews("users", $this->global, $data, NULL);
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
            $data = array();
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
    function addNewUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('firstname','Firstname','required',
                array('required' => 'Firstname is mandatory'));
            $this->form_validation->set_rules('email','Email','required',
                array('required' => 'Email is mandatory'));
            $this->form_validation->set_rules('lastname','Lastname','required',
                array('required' => 'Lastname is mandatory'));
            $this->form_validation->set_rules('gender','Gender','required',
                array('required' => 'Gender is mandatory'));
            $this->form_validation->set_rules('contact','Contact','required',
                array('required' => 'Contact number is mandatory'));
            $this->form_validation->set_rules('address','Address','required',
                array('required' => 'Address is mandatory'));
            $this->form_validation->set_rules('about_me','About me','required',
                array('required' => 'About me is mandatory'));
            $this->form_validation->set_rules('achievements','Achievements','required',
                array('required' => 'Achievements is mandatory'));
            $this->form_validation->set_rules('birthdate','Birthdate','required',
                array('required' => 'Birthdate is mandatory'));

            if($this->form_validation->run() == FALSE)
            {
               $this->addNew();
            }
            else
            {
                $firstname = ucwords(strtolower($this->input->post('firstname')));
                $lastname = ucwords(strtolower($this->input->post('lastname')));
                $email = $this->input->post('email');
                $gender = $this->input->post('gender');
                $contact = $this->input->post('contact');
                $address = $this->input->post('address');
                $about_me = $this->input->post('about_me');
                $achievements = $this->input->post('achievements');
                $birthdate = date('y-m-d',strtotime(str_replace('/','-',$this->input->post('birthdate'))));

                $userInfo = array();


                $userInfo = array('email'=>$email, 'gender'=>$gender, 'firstname'=>$firstname, 'lastname'=>$lastname,
                    'contact'=>$contact, 'address'=>$address, 'about_me'=>$about_me, 'achievements'=>$achievements,'birthdate'=>$birthdate);

                $this->load->model('User_model');
                $result = $this->User_model->addNewUser($userInfo);

                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New User created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }

                redirect('users');
            }
        }
    }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editUserform($userId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
         $this->loadThis();
        }
        else
        {

            if($userId == null)
            {
                redirect('users');
            }
            $data['userInfo'] = $this->User_model->getUserInfo($userId);

            $this->global['pageTitle'] = 'Locoyolo : Edit User';
            $this->loadViews("editUser", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
             $userId = $this->input->post('userId');

            $this->form_validation->set_rules('firstname','Firstname','required',
                array('required' => 'Firstname is mandatory'));
            $this->form_validation->set_rules('email','Email','required',
                array('required' => 'Email is mandatory'));
            $this->form_validation->set_rules('lastname','Lastname','required',
                array('required' => 'Lastname is mandatory'));
            $this->form_validation->set_rules('gender','Gender','required',
                array('required' => 'Gender is mandatory'));
            $this->form_validation->set_rules('contact','Contact','required',
                array('required' => 'Contact number is mandatory'));
            $this->form_validation->set_rules('address','Address','required',
                array('required' => 'Address is mandatory'));
            $this->form_validation->set_rules('about_me','About me','required',
                array('required' => 'About me is mandatory'));
            $this->form_validation->set_rules('achievements','Achievements','required',
                array('required' => 'Achievements is mandatory'));
            $this->form_validation->set_rules('birthdate','Birthdate','required',
                array('required' => 'Birthdate is mandatory'));



            if($this->form_validation->run() == FALSE)
            {
                $this->editUserform($userId);
            }
            else
            {
                $firstname = ucwords(strtolower($this->input->post('firstname')));
                $lastname = ucwords(strtolower($this->input->post('lastname')));
                $email = $this->input->post('email');
                $gender = $this->input->post('gender');
                $contact = $this->input->post('contact');
                $address = $this->input->post('address');
                $about_me = $this->input->post('about_me');
                $achievements = $this->input->post('achievements');
                $birthdate = date('y-m-d',strtotime(str_replace('/','-',$this->input->post('birthdate'))));

                $userInfo = array();


                    $userInfo = array('email'=>$email, 'gender'=>$gender, 'firstname'=>$firstname, 'lastname'=>$lastname,
                        'contact'=>$contact, 'address'=>$address, 'about_me'=>$about_me, 'achievements'=>$achievements,'birthdate'=>$birthdate);




                $result = $this->User_model->editUser($userInfo, $userId);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }

                redirect('users');
            }
        }
    }
    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('id');

            $result = $this->User_model->deleteUser($userId);

            if ($result > 0) { echo 'Y'; }
            else { echo "N"; }
        }
    }

    /**
     * This function is used to load the change password screen
     */
    function loadChangePass()
    {
        $this->global['pageTitle'] = 'CodeInsect : Change Password';

        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }


    /**
     * This function is used to change the password of the user
     */
    function editsettings()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('currentpassword','Current password','required');
        $this->form_validation->set_rules('newpassword','New password','required');
        $this->form_validation->set_rules('confirmnewpassword','Confirm new password','required|matches[newpassword]');
        if($this->form_validation->run() == FALSE)
        {//echo 'here';exit;
            $this->settings();
        }
        else
        {
            $oldPassword = $this->input->post('currentpassword');
            $newPassword = $this->input->post('newpassword');
            $sessiondata = $this->session->get_userdata();
            $userID=$sessiondata['userId'];
            $resultPas = $this->User_model->matchOldPassword($userID, $oldPassword);

            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your current password not correct');
           //     echo 'here1';exit;
                $this->settings();
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword));

                $result = $this->User_model->changePasswordAdmin($userID, $usersData);

                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
          //      echo 'here2';exit;
                $this->settings();
            }
        }
    }



    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function settings()
    {
        $this->global['pageTitle'] = 'Settings';

        $this->loadViews("settings/settings", $this->global, NULL, NULL);
    }
}
?>