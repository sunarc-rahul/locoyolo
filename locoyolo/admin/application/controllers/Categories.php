<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Event (EventController)
 * User Class to control all user related operations.
 * @author : Rahul Gahlot
 * @version : 1.1
 * @since : 7 spt 2017
 */
class Categories extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categories_model');
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
        $this->global['pageTitle'] = 'Locoyolo : Categories';

       // $this->loadViews("templates/header");
        $this->loadViews("categories/list", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the user list
     */
    function categoriesListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->input->post('searchText')?$this->input->post('searchText'):'';
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->Categories_model->categoryListingCount($searchText);
            $returns = $this->paginationCompress ( "categories/", $count, 5 );

            $data['categoriesRecords'] = $this->Categories_model->categoriesListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Locoyolo : Categories Listing';

            $this->loadViews("categories/list", $this->global, $data, NULL);
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
            $this->load->model('Categories_model');
            $this->global['pageTitle'] = 'Locoyolo : Add New Category';
            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to check whether email already exist or not
     */

    function deleteCat()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $CatId = $this->input->post('id');

            $result = $this->Categories_model->deleteCat($CatId);

            if ($result > 0) { echo 'Y'; }
            else { echo "N"; }
        }
    }

    function viewCat($catId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            if($catId == null)
            {
                redirect('categories');
            }
            $data['catInfo'] = $this->Categories_model->getcatInfo($catId);
            $this->global['pageTitle'] = 'Locoyolo : View Category';
            $this->loadViews("categories/view", $this->global, $data, NULL);
        }
    }
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function editCategoriesform($catId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {

            if($catId == null)
            {
                redirect('categories');
            }
            $data['catInfo'] = $this->Categories_model->getCatInfo($catId);

            $this->global['pageTitle'] = 'Locoyolo : Edit Category';
            $this->loadViews("categories/edit", $this->global, $data, NULL);
        }
    }
    function addCategoriesform()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {   $data = array();
            $this->global['pageTitle'] = 'Locoyolo : Add Category';
            $this->loadViews("categories/add", $this->global, $data, NULL);
        }
    }
    /**
     * This function is used to edit the user information
     */
    function editCategory()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            $catId = $this->input->post('catId');

            $this->form_validation->set_rules('event_type','Event Type','required',
                array('required' => 'Event type is mandatory'));
       /*    $this->form_validation->set_rules('map_icon','Map icon','required',
                array('required' => 'Map Icon is mandatory'));
            $this->form_validation->set_rules('type_icon','Type Icon','required',
                array('required' => 'Type icon is mandatory'));*/
            if($this->form_validation->run() == FALSE)
            {
                $this->editCategoriesform($catId);
            }
            else {
                $event_type = ucwords(strtolower($this->input->post('event_type')));
                $catInfo = array();

                //----------------------------------------------
                //----------------------------------------------
                $config = array(
                    'upload_path' => str_replace('\admin', '\images', FCPATH),
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE,
                    'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "768",
                    'max_width' => "1024"
                );
                $this->load->library('upload', $config);
                if($_FILES['map_icon']['name']!='') {
                if ($this->upload->do_upload('map_icon')) {
                    $data = array('upload_data' => $this->upload->data());
                    $catInfo['map_icon']= 'images/'.$_FILES['map_icon']['name'];

                } else {
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('categories/edit', $error);
                }
            }
                if($_FILES['type_icon']['name']!='') {
                    if ($this->upload->do_upload('type_icon')) {
                        $data = array('upload_data' => $this->upload->data());
                        $catInfo['type_icon']='images/'.$_FILES['type_icon']['name'];

                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        $this->load->view('categories/edit', $error);
                    }


                }
                //----------------------------------------------
                //----------------------------------------------
                $catInfo['event_type']= $event_type;




                $result = $this->Categories_model->editCategory($catInfo, $catId);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Category updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Category updation failed');
                }

                redirect('categories');
            }
        }
    }

    function addCategoryForm()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');


            $this->form_validation->set_rules('event_type','Event Type','required',
                array('required' => 'Event type is mandatory'));
       /*    $this->form_validation->set_rules('map_icon','Map icon','required',
                array('required' => 'Map Icon is mandatory'));
            $this->form_validation->set_rules('type_icon','Type Icon','required',
                array('required' => 'Type icon is mandatory'));*/
            if($this->form_validation->run() == FALSE)
            {
                $this->addCategoriesform();
            }
            else {
                $event_type = ucwords(strtolower($this->input->post('event_type')));
                $catInfo = array();

                //----------------------------------------------
                //----------------------------------------------
                $config = array(
                    'upload_path' => str_replace('\admin', '\images', FCPATH),
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE,
                    'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "768",
                    'max_width' => "1024"
                );
                $this->load->library('upload', $config);
                if($_FILES['map_icon']['name']!='') {
                if ($this->upload->do_upload('map_icon')) {
                    $data = array('upload_data' => $this->upload->data());
                    $catInfo['map_icon']= 'images/'.$_FILES['map_icon']['name'];

                } else {
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('categories/edit', $error);
                }
            }
                if($_FILES['type_icon']['name']!='') {
                    if ($this->upload->do_upload('type_icon')) {
                        $data = array('upload_data' => $this->upload->data());
                        $catInfo['type_icon']='images/'.$_FILES['type_icon']['name'];

                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        $this->load->view('categories/add', $error);
                    }


                }
                //----------------------------------------------
                //----------------------------------------------
                $catInfo['event_type']= $event_type;
                $result = $this->Categories_model->addCategory($catInfo);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Category added successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Category added failed');
                }

                redirect('categories');
            }
        }
    }

    function addCategory()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('event_type','Event Type','required',
                array('required' => 'Event type is mandatory'));
            /*    $this->form_validation->set_rules('map_icon','Map icon','required',
                     array('required' => 'Map Icon is mandatory'));
                 $this->form_validation->set_rules('type_icon','Type Icon','required',
                     array('required' => 'Type icon is mandatory'));*/
            if($this->form_validation->run() == FALSE)
            {
                $this->addCategoryForm();
            }
            else {
                $event_type = ucwords(strtolower($this->input->post('event_type')));
                $catInfo = array();

                //----------------------------------------------
                //----------------------------------------------
                $config = array(
                    'upload_path' => str_replace('\admin', '\images', FCPATH),
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => TRUE,
                    'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "768",
                    'max_width' => "1024"
                );
                $this->load->library('upload', $config);
                if($_FILES['map_icon']['name']!='') {
                    if ($this->upload->do_upload('map_icon')) {
                        $data = array('upload_data' => $this->upload->data());
                        $catInfo['map_icon']= 'images/'.$_FILES['map_icon']['name'];

                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        $this->load->view('categories/edit', $error);
                    }
                }
                if($_FILES['type_icon']['name']!='') {
                    if ($this->upload->do_upload('type_icon')) {
                        $data = array('upload_data' => $this->upload->data());
                        $catInfo['type_icon']='images/'.$_FILES['type_icon']['name'];

                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        $this->load->view('categories/edit', $error);
                    }


                }
                //----------------------------------------------
                //----------------------------------------------
                $catInfo['event_type']= $event_type;




                $result = $this->Categories_model->addCategory($catInfo);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Category updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Category updation failed');
                }

                redirect('categories');
            }
        }
    }
}
?>