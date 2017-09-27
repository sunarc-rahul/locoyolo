<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
        $this->db->select('public_users.email');
        $this->db->from('public_users ');
        if(!empty($searchText)) {
            $likeCriteria = "(public_users.email  LIKE '%".$searchText."%'
                            OR  public_users.firstname  LIKE '%".$searchText."%'
                            OR  public_users.lastname  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);

        }
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($searchText = '', $page, $segment)
    {
        $this->db->select('public_users.id, public_users.email, concat(public_users.firstname," ",public_users.lastname) as name');
        $this->db->from('public_users ');
        if(!empty($searchText)) {
            $likeCriteria = "(public_users.email  LIKE '%".$searchText."%'
                            OR  public_users.firstname  LIKE '%".$searchText."%'
                            OR  public_users.lastname  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
       $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }



    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('public_users', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('*');
        $this->db->from('public_users');
        $this->db->where('id', $userId);
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('id', $userId);
        $this->db->update('public_users', $userInfo);

        return TRUE;
    }



    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId)
    {
        $this->db->where('id', $userId);
        $this->db->delete('public_users');
        return  $this->db->affected_rows();
    }
    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('id, password');
        $this->db->where('id', $userId);
        $query = $this->db->get('admin_users');

        $user = $query->result();
       if(!empty($user)){

            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('id', $userId);
        $this->db->update('public_users', $userInfo);

        return $this->db->affected_rows();
    } function changePasswordAdmin($userId, $userInfo)
    {
        $this->db->where('id', $userId);
        $this->db->update('admin_users', $userInfo);

        return $this->db->affected_rows();
    }
}
