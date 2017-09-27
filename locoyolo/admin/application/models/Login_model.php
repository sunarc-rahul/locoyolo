<?php

 if(!defined('BASEPATH')) exit('No direct script access allowed');
class Login_model extends CI_Model
{

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($email, $password)
    {
        $this->db->select('admin_users.*, Roles.*');
        $this->db->from('admin_users');
        $this->db->join('Roles','Roles.id = admin_users.roleId');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $user = $query->result();

         if(!empty($user)){

            if(verifyHashedPassword($password, $user[0]->password)){


                return $user;
            } else {

                return array();
            }
        } else {
            return array();
        }
    }
    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    {
        $this->db->select('id');
        $this->db->where('email', $email);
        $query = $this->db->get('admin_users');
        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }
    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('id, email, name');
        $this->db->from('admin_users');
        $this->db->where('email', $email);
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->update('admin_users', array('password'=>getHashedPassword($password)));
        $this->db->delete('tbl_reset_password', array('email'=>$email));
    }
}
?>