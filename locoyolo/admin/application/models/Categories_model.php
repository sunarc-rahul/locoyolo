<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Categories_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function categoryListingCount($searchText = '')
    {
        $this->db->select('event_types.*');
        $this->db->from('event_types');
        if(!empty($searchText)) {
            $likeCriteria = "(event_types.event_type LIKE '%".$searchText."%')";
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
    function categoriesListing($searchText = '', $page, $segment)
    {
        $this->db->select('event_types.*');
        $this->db->from('event_types');
        if(!empty($searchText)) {
            $likeCriteria = "(event_types.event_type LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);

        }
       $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getCatInfo($catid)
    {
        $this->db->select('*');
        $this->db->from('event_types');
        $this->db->where('id', $catid);
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editCategory($catInfo, $catId)
    {
        $this->db->where('id', $catId);
        $this->db->update('event_types', $catInfo);

        return TRUE;
    }

    function addCategory($catInfo)
    {
        $this->db->trans_start();
        $this->db->insert('event_types', $catInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }




    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteCat($catId)
    {
        $this->db->where('id', $catId);
        $this->db->delete('event_types');
        return  $this->db->affected_rows();
    }

    function getPingInfo($pingId)
    {
        $this->db->select('events.*, event_type, firstname,lastname');
        $this->db->from('events');
        $this->db->join('public_users', 'public_users.id = events.user_id', 'left');
        $this->db->join('event_types', 'event_types.id = events.event_category', 'left');
        $this->db->where('event_id', $pingId);
        $query = $this->db->get();
        return $query->result();
    }

}
