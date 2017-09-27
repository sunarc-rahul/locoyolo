<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ping_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function pingListingCount($searchText = '')
    {
        $this->db->select('events.event_name');
        $this->db->from('events');
        $this->db->where("events.entry_type='Ping'");
        if(!empty($searchText)) {
            $likeCriteria = "(events.event_name LIKE '%".$searchText."%')";
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
    function pingListing($searchText = '', $page, $segment)
    {
        $this->db->select('events.event_id as id, events.event_name,events.start_date');
        $this->db->from('events');
        $this->db->where("events.entry_type='Ping'");
        if(!empty($searchText)) {
            $likeCriteria = "(events.event_name  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
       $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deletePing($pingId)
    {
        $this->db->where('event_id', $pingId);
        $this->db->delete('events');
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
