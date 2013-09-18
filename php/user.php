<?php

class userNotes{

    /**
     * Database connection credentials
     */
    public $dbUser = 'username';
    public $dbPass = 'password';
    public $dbHost = 'host';
    public $dbPort = null;
    
    /**
     * Name of the database we will be connecting to
     * @var string 
     */
    public $dbDatabase = 'notetoself';
    
    /**
     * Data source name for connecting to PDO. @see self::generateDSN();
     * @var string 
     */
    public $dsn;
    
    /**
     * Holds PDO object
     * @var PDO 
     */
    public $conn;
    
    /**
     * Construct and connect to our database
     * @throws \Exception if unable to connect
     */
    public function __construct()
    {
        try {
            $this->conn = new PDO($this->generateDSN(), $this->dbUser, $this->dbPass);
        } catch (PDOException $e) {
            throw new Exception('There was an error connecting to the database. ('.$e->getMessage().')');
        }
    }
    
    /**
     * Generate DSN required for connecting to PDO
     * @return string
     */
    protected function generateDSN()
    {
        $dsn = sprintf('mysql:dbname=%s;host=%s',$this->dbDatabase,$this->dbHost);
        !is_null($this->dbPort) and $dsn .= ';port=' . $this->dbPort;
        return $this->dsn = $dsn;
    }
    
    /**
     * Attempt to save notes if user does not have existing record, otherwise update
     * @param string $id
     * @param array $notes
     * @return string
     */
    public function userSave($id, $notes)
    {
        $notes = json_encode($notes);
        if($this->userExists($id))
        {
            $query = 'UPDATE usernotes SET notes = :notes WHERE id = :id';
            $bind = array(':notes'=>$notes,':id'=>$id);
        }
        else
        {
            $query = 'INSERT INTO usernotes(id, notes) VALUES(:id, :notes)';
            $bind = array(':id'=>$id,':notes'=>$notes);
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($bind);
        $response = $stmt->rowCount();
        return ($response > 0 ? 'Successfully saved your notes to the servers database!' : 'We ran in to an issue saving your notes, please try again.');
    }
    
    /**
     * Check if user exists in database
     * @param string $id
     * @return mixed(obect|bool)
     */
    public function userExists($id)
    {
        $query = 'SELECT * FROM usernotes WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array(':id'=>$id));
        $count = $stmt->rowCount();
        return ($count > 0 ? $stmt : FALSE);
    }
}
/**
 * End userNotes class
 */

/**
 * Make sure this is a POST request, otherwise, it is likely not coming
 * from the application.
 */
if($_POST)
{
    /**
     * Grab post vars and ip for use as an ID
     */
    $action = $_POST['action'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : NULL;
    $id = $_SERVER['REMOTE_ADDR'];
    
    /**
     * Is this a legitimate request?
     */
    if(in_array($action,array('save','load')))
    {
        try{
            /**
             * Instantiate object. If the action is a save/update, return the string response. If the action
             * is load then return results(error or notes from DB) in JSON format
             */
            $userNotes = new userNotes();
            
            if($action === 'save')
            {
                $response = $userNotes->userSave($id,$notes);
                echo $response;
            }
            else
            {
                $response = $userNotes->userExists($id);
                if($response)
                {
                    print_r(json_encode($response->fetch(PDO::FETCH_OBJ)));
                }else
                {
                    print_r(json_encode(array('error'=>'Sorry, you don\'t seem to have any saved notes!')));
                }
            }
            
        } catch(Exception $e) {
            print_r(json_encode(array('error'=>$e->getMessage())));
        }
    }
    else
    {
        echo 'Invalid action...what are you trying to do?!';
    }
}