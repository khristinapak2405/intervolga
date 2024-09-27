<?php
require_once "database.php";

abstract class TableModule
{
	abstract protected function getTableName(): string;


	public function delete($id)
	{

        $query = 'DELETE FROM '. $this->getTableName() . ' WHERE id='. $id;
        mysqli_query(Database::connection(), $query) or die("You  cannot delete that.");


	}
    public function deleteImage($id)
    {
        $remove_img_query= 'SELECT * FROM '. $this->getTableName(). ' WHERE id='.$id;
        $remove_img_result= mysqli_query(Database::connection(), $remove_img_query) or die(mysqli_error(Database::connection()));
        $remove_img_result=mysqli_fetch_assoc($remove_img_result);
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/API/inc/images/'.$remove_img_result['img_path'])) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/API/inc/images/' . $remove_img_result['img_path']);
        }
    }

	public function read($needcrews = false,$crewfilter='default')
	{
        $fields = array();
        $sql = "SELECT * FROM " . $this->getTableName();

        if ($needcrews === true)
        {

            $sql = "SELECT loans.*, customers.customer, customers.amount FROM loans LEFT JOIN customers ON loans.customer = customers.customer; ";
        }
        if ($crewfilter !== 'default')
        {
            $sql.=  ' WHERE idGroup='. $crewfilter;
        }


        foreach ($fields as $key => $field) {
            $sql .= "AND " . $key . "=" . $field . " ";
        }

        $queryresult = mysqli_query(Database::connection(), $sql);
        $result = array();
        while ($slice =mysqli_fetch_assoc($queryresult)) {
            $result[] = $slice;
        }
        return $result;
	}


	public function create($message)
	{
        $fields =  array_keys($message);
        $values = $message;
        $query = "INSERT INTO ".$this->getTableName(). " (";
        foreach($fields as $column)
        {
            $query .= $column;
            $query .= ", ";
        }
        $query = substr($query, 0, -2);
        $query .= ")"."VALUES "."(";
        foreach($values as $column)
        {
            $query .= "'";
            $query .= $column;
            $query .= "', ";
        }
        $query = substr($query, 0, -2);
        $query .=  ")";
        mysqli_query(Database::connection(), $query) or die(mysqli_error(Database::connection()));
	}
	public function update($message)
	{
        $fields =  array_keys($message);

        $values = $message;
        var_dump($values);
        $id = $message["id"];
        $count = 0;
        $query = "UPDATE ".$this->getTableName(). " SET ";
        foreach($fields as $column)
        {
            $keys = array_keys($values);
            $keys = $keys[array_search("id",$keys)+$count];

            $query .= $column. " = "."'".$values[$keys];
            $query .= "', ";
            $count++;
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE id=". $id;
        mysqli_query(Database::connection(), $query) or die(mysqli_error(Database::connection()));
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function exists($id): bool
	{
        $queryresult = mysqli_query(Database::connection(),'SELECT * FROM ' . $this->getTableName() . ' WHERE id=' . $id);
        $find = mysqli_fetch_assoc($queryresult);
		return boolval($find);
	}
	/**
	 * @return int
	 */
	public function lastID():int
	{
        $query = "SELECT MAX(ID) FROM " . $this->getTableName();
        $queryresult = mysqli_query(Database::connection(), $query);
		$find = mysqli_fetch_assoc($queryresult);
		return intval($find["MAX(ID)"]);
	}

}