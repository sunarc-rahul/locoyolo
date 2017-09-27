<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Event_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function eventListingCount($searchText = '')
    {
        $this->db->select('events.event_name');
        $this->db->from('events');

        $this->db->where("events.entry_type!='Ping'");
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
    function eventListing($searchText = '', $page, $segment)
    {
        $this->db->select('events.event_id as id, events.event_name,events.start_date');
        $this->db->from('events');
        $this->db->where("events.entry_type!='Ping'");
        if(!empty($searchText)) {
            $likeCriteria = "(events.event_name  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
       $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getEventInfo($eventId)
    {
        $this->db->select('events.*, event_type, firstname,lastname');
        $this->db->from('events');
        $this->db->join('public_users', 'public_users.id = events.user_id', 'left');
        $this->db->join('event_types', 'event_types.id = events.event_category', 'left');
        $this->db->where('event_id', $eventId);
        $query = $this->db->get();
        return $query->result();
    }

    function getEventCategory()
    {
        $this->db->select('id,event_type');
        $this->db->from('event_types');
        $query = $this->db->get();
        return $query->result();
    }
    function deleteEvent($eventId)
    {
        $this->db->where('event_id', $eventId);
        $this->db->delete('events');
        return  $this->db->affected_rows();
    }

}
