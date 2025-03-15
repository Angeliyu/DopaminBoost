<?php

class MY_Model extends CI_Model {

    //property declare the main table
    protected $tablename = "";
    public $primaryKey = "id";

    public function __construct() {
        $this->load->database();
    }

    // only receive the one row of data //
    //row_result only get 1 row of data (associative array)
    public function getOne($where=array()) {

        //query builder
        $this->db->select("*");
        $this->db->where($where);
        $query = $this->db->get($this->tablename);
        return $query->row_array();

    }
    // only receive the one row of data end //

    // select all //
    public function get_where($where=array()) {

        //query builder
        $this->db->select("*");
        $this->db->where($where);
        $query = $this->db->get($this->tablename);
        return $query->result_array();

    }
    // select all end //

    // select by multiple where //
    public function get_where_in($where = array(), $where_in = array(), $select = "*", $order_by = "created_date", $ascdec = "ASC", $group_by = "")
    {
        $this->db->select($select);
        
        // Apply WHERE conditions
        if (!empty($where)) {
            $this->db->where($where);
        }

        // Apply WHERE IN conditions
        if (!empty($where_in)) {
            foreach ($where_in as $key => $values) {
                if (!empty($values)) { // Ensure values are not empty before applying where_in
                    $this->db->where_in($key, $values);
                }
            }
        }

        // Apply GROUP BY (before executing query)
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        // Apply ORDER BY
        $this->db->order_by($order_by, $ascdec);

        // Execute the query
        $query = $this->db->get($this->tablename);

        return $query->result_array();
    }
    // by multiple where end //

    // insert query //
    //INSERT INTO tablename (col1, col2, col3) VALUES (val1, val2, val3)
    public function insert($insert_array=array()) {

        $this->db->insert($this->tablename, $insert_array);
        return $this->db->insert_id();
        
    }
    // insert query end //

    // update query //
    //UPDATE tablename SET col1=val1, col2=val2 WHERE id = x
    public function update($where=array(), $update_array=array()) {
        $this->db->where($where);
        $this->db->update($this->tablename, $update_array);
        return $this->db->affected_rows() > 0; // returns true if rows were updated
    }
    // update query end //


    //for counting total data
    public function record_count($where = array(), $like = array(), $where_in = array(), $or_like = array())
    {

        $this->db->select("COUNT(*) AS total");
        $this->db->where($where);

        if (!empty($where_in)) {
        $this->db->where_in($where_in);
        }

        if (!empty($like)) {
        foreach ($like as $k => $v) {
            $this->db->like($k, $v);
        }
        }

        if (!empty($or_like)) {
        $i = 1;
        $this->db->group_start();
        foreach ($or_like as $k => $v) {
            if ($i == 1) {
            $this->db->like($k, $v);
            } else {
            $this->db->or_like($k, $v);
            }
            $i++;
        }
        $this->db->group_end();
        }

        $query = $this->db->get($this->tablename);
        $row = $query->row_array();
        return $row['total'];
    }

    // get the sum of qty under the same sid //
    public function get_sum_qty($where=array()) {

        $this->db->select("SUM(qty) AS total_qty");
        $this->db->where($where);
        $query = $this->db->get($this->tablename);
        $row = $query->row_array();

        // Return 0 if the result is NULL or not set
        return isset($row['total_qty']) ? $row['total_qty'] : 0;
        
    }
    // get the sum of qty under the same sid //


    public function fetch2($select = "*")
    {
        if ($select != "*") {
            $this->db->select($select);
        }

        $this->db->where(['is_deleted' => 0]);

        $query = $this->db->get($this->tablename);


        return $query->result_array();
    }

    //for pagination
    public function fetch($limit, $start, $where = array('is_deleted' => 0), $like = array(), $orderBy = array(), $select = "*", $groupBy = "", $where_in = array(), $where_in_col = "", $or_like = array()) {

        if ($select != "*") {
        $this->db->select($select);
        }
        $this->db->where($where);
        if (!empty($like)) {
        if (is_array($like)) {
            foreach ($like as $k => $v) {
            $this->db->like($k, $v);
            }
        } else {
            $this->db->where($like);
        }
        }

        if (!empty($or_like)) {
        $i = 1;
        $this->db->group_start();
        foreach ($or_like as $k => $v) {
            if ($i == 1) {
            $this->db->like($k, $v);
            } else {
            $this->db->or_like($k, $v);
            }
            $i++;
        }
        $this->db->group_end();
        }

        if (!empty($where_in)) {
        $this->db->where_in($where_in_col, $where_in);
        }

        if (!empty($orderBy)) {
        foreach ($orderBy as $k => $v) {
            $this->db->order_by($k, $v);
        }
        } else {
        $this->db->order_by($this->primaryKey, "DESC");
        }

        if (!empty($groupBy)) {
        $this->db->group_by($groupBy);
        }


        $this->db->limit($limit, $start);
        $query = $this->db->get($this->tablename);

        if ($query->num_rows() > 0) {
        return $query->result_array();
        } else {
        return array();
        }
    }

    public function getIDKeyArray($column = "title", $where = array('is_deleted' => 0), $order_by = "", $ascdec = "DESC", $like = array(), $divider = " ") {
        $id_list = array();
        $list = $this->get_where($where, $like, $order_by, $ascdec);
        foreach ($list as $k => $v) {

        if (strpos($column, ",") !== FALSE) {
            $title = "";
            $tmp = explode(",", $column);
            $i = 0;
            foreach ($tmp as $v2) {
            if ($i == 0) {
                $title .= $v[$v2];
            } else {
                $title .= $divider . $v[$v2];
            }

            $i++;
            }
        } else {
            $title = $v[$column];
        }

        $id_list[$v[$this->primaryKey]] = $title;
        }
        return $id_list;
    }

    public function getIDKeyArrayComma($column = "title", $where = array('is_deleted' => 0), $order_by = "", $ascdec = "DESC", $like = array(), $divider = ",") {
        $id_list = array();
        $list = $this->get_where($where, $like, $order_by, $ascdec);
        foreach ($list as $k => $v) {

        if (strpos($column, ",") !== FALSE) {
            $title = "";
            $tmp = explode(",", $column);
            $i = 0;
            foreach ($tmp as $v2) {
            if ($i == 0) {
                $title .= $v[$v2];
            } else {
                $title .= $divider . $v[$v2];
            }

            $i++;
            }
        } else {
            $title = $v[$column];
        }

        $id_list[$v[$this->primaryKey]] = $title;
        }
        return $id_list;
    }

}


?>